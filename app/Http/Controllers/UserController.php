<?php

namespace App\Http\Controllers;

use App\Events\OurExampleEvent;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();
        $filename = $user->id."-".uniqid()."-".date('n-j-Y').'.jpg';

        $image = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/'.$filename,$image);

        $oldAvatar = $user->avatar;
        
        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/no-image.jpg"){
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }
        
        return back()->with('success', 'Avatar successfuly updated');
    }

    public function showAvatarForm(){
        return view('avatar-form');
    }

    private function getSharedData($user){
        $currentlyFollowing = 0;

        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->count();
        }

        View::share('sharedData',compact(['currentlyFollowing','user']));
    }

    public function profile(User $user) {
        $this->getSharedData($user);

        return view('profile-posts', compact(['user']));
    }

    public function profileFollowers(User $user) {
        $this->getSharedData($user);

        $followers = $user->followers()->latest()->get();

        return view('profile-followers', compact(['followers']));
    }

    public function profileFollowing(User $user) {
        $this->getSharedData($user);

        $followings = $user->following()->latest()->get();

        return view('profile-following', compact(['followings']));
    }

    public function showCorrectHomepage(){
        if(auth()->check()) {
            $feedPosts = auth()->user()->feedPost()->latest()->paginate(3);
            return view('homepage-feed', compact('feedPosts'));
        } else {
            return view('homepage');
        }
    }
    
    public function login (Request $request) {
        $login = $request->validate([
            'loginusername'=>'required',
            'loginpassword'=>'required'
        ]);
        if(auth()->attempt(['username'=>$login['loginusername'], 'password'=>$login['loginpassword']])){
            event(new OurExampleEvent(['username'=> auth()->user()->username, 'action' => 'login']));
            return redirect('/')->with('success','You\'re logged in');
        } else {
            return redirect('/')->with('failed','Failed to login, please check again your username and password');
        }        
    }

    public function logout () {
        event(new OurExampleEvent(['username'=> auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('logout','You\'re logged out');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users','username')],
            'email' => ['required', 'email', Rule::unique('users','email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('registered','You\'re registerd successfully');
    }
}
