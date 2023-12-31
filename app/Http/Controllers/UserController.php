<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\User;
use App\Models\User_Event_Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'event_id' => 'required',
        ]);

        $email = $request->input('email');
        $event_id = $request->input('event_id');

        $user = User::where('email', $email)->first()->id;
        $event = Events::find($event_id)->id;

        if (!$user or !$event) return response()->json(['message' => 'Something went wrong'], 404);

        $array = array('user_id' => $user,'event_id' => $event,'registered_date' => now());
        $exists = User_Event_Registration::where('user_id', $user)
            ->where('event_id', $event)
            ->exists();

        if ($exists){
            return response()->json(['message' => 'User already registered']);
        }
        User_Event_Registration::insert($array);

        return response()->json(['message' => 'User registered']);
    }
}
