<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRegister;
use Auth;
use Hash;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegister $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $newData = User::create($data);

        return view('login');
    }

    /**
     * Fetch authenticated user info
     *
     * @return \Illuminate\Http\Response
     */
    public function userInfo()
    {
        $user = request()->user();
        return response()->json($user);
    }

    /**
     * Show the login form
     *
     */
    public function loginView()
    {
        return view('login');
    }

    /**
     * Attempt login
     * @param $request
     */
    public function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    /**
     * Log out the current authenticated user
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
