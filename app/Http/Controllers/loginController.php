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

            $user=User::where('email', $request->email)
                ->where('password', $request->password)
                ->first();
                //return $user;
            if($user)
            {
                Session()->put('user', $user->id);
                Session()->put('login_type', 'buyer');
                return redirect()->route('order.new');
            }
            return redirect()->route('login');
    }
    public function logout()
    {
        Session()->flush();
        return redirect()->route('login');
    }
}
