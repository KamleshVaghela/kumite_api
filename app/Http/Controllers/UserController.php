<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Config;
use Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken($request->name)->plainTextToken;

        return response([
            'success' =>true,
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response([
            'success' =>true,
            'message' => 'Successfully logged out'
        ]);
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => 'Your provided credential are invalid'
            ],401);
        }
        $token = $user->createToken($user->name)->plainTextToken;

        return response([
            'success' =>true,
            'user' => $user,
            'token' => $token
        ], 200);
    }
    
    public function web_login() {
        return View('login');
    }
    
    public function web_user_login(Request $request) {
        // $check = $request->all();
        // dd($check['password']);
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // return redirect()->intended('dashboard')
            //             ->withSuccess('Signed in');
            return redirect()->route('admin.dashboard')->with('error', 'Login Successfully');
        }

        // if(Auth::attempt(['email' => $check['email'], 'password' => Hash::make($check['password']),'status'=>'1' ])) {
        //     return redirect()->route('welcome')->with('error', 'Login Successfully');
        // }
        else {
            return back()->with('error', 'Invalid Credential');
        }
    }

}
