<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        $menus = Menu::with('children')->whereNull('parent_id')->get(); // Fetch menus with children

        return view('registration', compact('menus') );
    }
    public function store(Request $request){
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
        ]);
        
        return redirect()->route('login')->with('success','registration completed');
    }
    public function login(){

        return view('login');
    }
    public function attamped(Request $request){
        $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
        ]);

        if(Auth::attempt($request->only('email','password'))){
            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        };  

        return redirect()->back()->with('error', 'Invalid email or password.');
    }
}
