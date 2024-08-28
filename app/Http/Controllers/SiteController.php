<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SiteController extends Controller
{
    public function role()
    {
//        return User::role('admin')->get(); // Returns only users with the role 'writer'
//        return User::role('user')->get(); // Returns only users with the role 'writer'

        $user = Auth::user();
        if($user->hasRole('admin')){
            return 'this is a admin';
        }else if($user->hasRole('user')){
            return 'this is user';
        }else{
            return 'no';
        }
    }

    public function test()
    {
//        return Storage::allFiles('profile_picture');

//        return Storage::get('one.txt');
        return storage_path('docs/profile_picture/YYknnTQc4uTHW3RBbJvDoAbRiT0683TvdPziyEyf.jpg');
    }
}
