<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(){

        $liked = Auth::user()->post;
        if (count($liked) < 2){
            return back()->with('status', 'Like two posts in order to update profile');
        }


        if(Auth::user()){
            $users = User::find(1);
            return view('users.profile',['users' => $users]);
        } else{
            abort(Response::HTTP_FORBIDDEN);
        }
    }

    public function profileUpdate(Request $request){

        $user = Auth::user();
        $liked = Auth::user()->posts;

        if(count($liked) >= 2){
            $request->validate([
                'name' =>'required',
                'email'=>'required',
            ]);
            $user =Auth::user();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->save();
            return back()->with('status','Profile Updated');
        }
        return back()->with('status', "To update profile, like two posts");
    }
}
