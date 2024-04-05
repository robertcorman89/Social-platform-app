<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        if ($user->id == auth()->user()->id) {
            return back()->with('error', 'You cannot follow yourself!');
        }
        $followedUsersCount = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($followedUsersCount > 0) {
            return back()->with('error', 'You already follow this user!');
        }
        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();
        return back()->with('success', 'You started following this user!');
    }

    public function removeFollow(User $user)
    {
        $follow = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]]);
        $follow->delete();
        return back()->with('success', 'You stopped following this user!');
    }
}
