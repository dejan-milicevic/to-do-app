<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers;
use Input;
use Redirect;
use Hash;
use Auth;

class UsersController extends Controller {

	/**
    * Show the profile for the given user.
    *
    * @param  int  $id
    * @return Response
    */
    
    public function authenticate() {
       // dd(Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')]));
        
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')])) {
            // Authentication passed...
            return Redirect::to('/users');
        }
        return Redirect::back()->withInput();
    }
    
    public function home() { // Route: '/'
//        if (Auth::check()) return Redirect::to('users');
        return Redirect::to('/users');
    }

    public function login() { // Route: '/login'
//        if (Auth::check()) return Redirect::to('users');
        return view('/login');
    }
    
    public function logout() { // Route: '/logout'
        Auth::logout();
        return Redirect::to('/login');
    }
    
    public function index() { // Route: '/users'
        if (Auth::check()) {
            $users = User::all();
            $user = Auth::user();
            return view('/users/index', ['users' => $users, 'user' => $user]);
        }
        return view('login');
    }

//    public function show($first_name) { // Route: '/users/{first_name}'
//        $user = User::whereFirst_name($first_name)->first();
//        return view('users.show', ['user' => $user]);
//    }

    public function create() { // Route: '/create'
        if (Auth::check()) return Redirect::to('users');
        return view('/users/create');
    }
    
    public function store() { // Route: '/users/store'
        
        if ( ! User::isValid(Input::all())) {
            return Redirect::back()
                ->withInput()
                ->withErrors(User::$errors);
        }
        
        $user = new User;
        $user->email = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->first_name = Input::get('first_name');
        $user->last_name = Input::get('last_name');
        $user->company = Input::get('company');
        $user->country = Input::get('country');
        $user->save();
        
        Auth::login($user);
        return Redirect::to('users');
        //return Redirect::route('users.index');
    }
}