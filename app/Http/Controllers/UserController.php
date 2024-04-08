<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{


    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out!');
    }
    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return view('homepage-feed');
        } else {
            return view('homepage');
        }
    }
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:30', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating a new account');
    }

    public function login(Request $request)
    {
        $loginFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);
        if (auth()->attempt(['username' => $loginFields['loginusername'], 'password' => $loginFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have succesfully logged in!');
        } else {
            return redirect('/')->with('error', 'Invalid credentials provided!');
        }
    }


    private function getSharedData(User $user)
    {
        $userFollowing = 0;
        $userPosts = $user->posts()->latest()->get();
        $userFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        View::share('sharedData', [
            'userFollowed' => $userFollowing,
            'avatar' => $user->avatar,
            'username' => $user->username,
            'postCount' => $userPosts->count(),
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count()
        ]);
    }

    public function profile(User $user)
    {
        $this->getSharedData($user);
        $userPosts = $user->posts()->latest()->get();
        return view('profile-posts', [
            'posts' => $userPosts
        ]);
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }
    public function profileFollowers(User $user)
    {
        $this->getSharedData($user);
        $followers = $user->followers()->latest()->get();
        return view('profile-followers', ['followers' => $followers]);
    }
    public function profileFollowing(User $user)
    {
        $this->getSharedData($user);
        $following = $user->following()->latest()->get();
        return view('profile-following', ['following' => $following]);
    }


    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();
        $filename = $user->id . '-' . uniqid() . '.jpg';
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode();
        Storage::put('public/avatars/' . $filename, $imgData);
        $oldAvatar = $user->avatar;
        $user->avatar = $filename;
        $user->save();
        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }
        return back()->with('success', 'Avatar updated!');
    }
}
