<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\admin\Investment;
use App\Models\admin\Admin;
use App\Models\admin\User;
class Dashboard extends Controller
{
    
    function index(){
        $data['totalInvestment'] = Investment::sum('amount');
        $data['totalUser'] = User::count();
        $data['totalSalePerson'] = Admin::where('role','0')->count();
        $data['totalFinancePerson'] = Admin::where('role','3')->count();
        // dd($data['totalSalePerson']);
        $data['appointment']= '45';
        return view('admin/dashboard',$data);
    }

    function user_dashboard(){
        $data['page'] = 'admin/user_dashboard';
        
        return view('admin/main_layout',$data);
    }

    
}
