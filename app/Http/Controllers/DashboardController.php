<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if($user->hasRole('admin')){
            $user = 'admin';
            return view('dashboard',compact('user'));
        }else if($user->hasRole('user')){
            $user = 'user';
            return view('dashboard',compact('user'));
        }else{
            return  'Unauthorized';
        }
//        return view('dashboard');
    }
}
