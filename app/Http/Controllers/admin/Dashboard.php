<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Dashboard extends Controller
{
    
    function index(){
        return view('admin/dashboard',['appointment'=>45]);
    }

    function user_dashboard(){
        $data['page'] = 'admin/user_dashboard';
        return view('admin/main_layout',$data);
    }

    
}
