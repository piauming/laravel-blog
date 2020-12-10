<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class RegisterController extends Controller
{
    // when user is signed-in, should not see register. so register is only avail to guest
    // using middleware auth in construct, the index and store method is not accessible as guest
    public function __construct() {
        $this->middleware(['guest']);
    }


    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {
        //return view('auth.register');
        //$request->get('email'); 
        // $request->email;
        
        // validate
        $this->validate($request,[
        'name' => 'required|max:255',
        'username' => 'required|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|confirmed',

        ]);

        // store
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
 
        // sign user in
        auth()->attempt($request->only('email', 'password'));
        
        // return redirect('/dashboard');
        return redirect()->route('dashboard');
    }
}
