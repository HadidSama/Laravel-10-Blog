<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow (User $user) {
        // you cannot follow yourself
        if ($user->id == auth()->user()->id){
            return back()->with('failed', 'You cannot follow yourself');
        }
        //you cannot follow someone you're already following
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id],['followeduser','=',$user->id]])->count();

        if ($existCheck) {
            return back()->with('failed', 'You are already following that user');
        }

        $follow = new Follow;
        $follow->user_id = auth()->user()->id;
        $follow->followeduser = $user->id;
        $follow->save();

        return back()->with('success','User successfully followed');
    }

    public function unfollow(User $user){
        Follow::where([['user_id', '=', auth()->user()->id],['followeduser','=',$user->id]])->delete();

        return back()->with('success', 'User successfully unfollowed');
    }
}
