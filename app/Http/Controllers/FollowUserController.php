<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;

class FollowUserController extends Controller
{
    public function store(User $user) {
        // dd($user);

        Follower::create([
            'user_id' => $user->id,
            'follower_id' => auth()->user()->id
            ]);

        return back();
    }

    public function destroy(User $user) {
        $user->followers()->where('follower_id', auth()->user()->id)->delete();
        //$user is not the login user

        return back();
    }
}
