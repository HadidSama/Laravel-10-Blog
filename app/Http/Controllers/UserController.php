<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

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

    public function profile(User $user) {
        $posts = $user->posts()->get();
        return view('profile-posts', compact(['user','posts']));
    }

    public function showCorrectHomepage(){
        if(auth()->check()) {
            return view('homepage-feed');
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
            return redirect('/')->with('success','You\'re logged in');
        } else {
            return redirect('/')->with('failed','Failed to login, please check again your username and password');
        }        
    }

    public function logout () {
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
