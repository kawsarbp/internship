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
            return view('dashboard');
        }else if($user->hasRole('user')){
            return 'user page';
        }else{
            return  'Unauthorized';
        }
//        return view('dashboard');
    }
}
