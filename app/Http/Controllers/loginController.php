<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    public function login()
    {
        return view(view: 'welcome');
    }
    public function authenticate(Request $request)
    {

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                auth()->login($user);
                Session()->put('login_type', $request->login_type);
                return redirect()->route('order.new');
            }
            return redirect()->route('login');
    }
    public function logout()
    {
        auth()->logout();
        Session()->flush();
        return redirect()->route('login');
    }
}
