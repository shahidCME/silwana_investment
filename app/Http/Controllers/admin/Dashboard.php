<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\admin\Investment;
use App\Models\admin\Admin;
use App\Models\admin\User;
use App\Models\admin\Schema;
use DB;
class Dashboard extends Controller
{
    
    function index(){
        $data['totalInvestment'] = Investment::sum('amount');
        $data['totalUser'] = User::count();
        $data['totalSalePerson'] = Admin::where('role','0')->count();
        $data['totalFinancePerson'] = Admin::where('role','3')->count();
        $data['totalApprover'] = Admin::where('role','4')->count();
        $data['totalSchema'] = Schema::count();
        $data['totalCancelledInvestment'] = Investment::where('status','9')->count();
        $data['appointment']= '45';
        return view('admin/dashboard',$data);
    }

    function user_dashboard(){
        $user_id = admin_login()['id']; 
        // dd(admin_login());
        $data['totalInvestment'] = Investment::where('user_id',$user_id)->sum('amount');
        $data['page'] = 'admin/user_dashboard'; 
        $data['numberOfInvestment'] = Investment::where('user_id',$user_id)->count();
        $data['totalReturn'] = DB::table('investments as i')
            ->join('roi as r', 'i.id', '=', 'r.investment_id')
            ->where(['i.user_id'=>$user_id,'r.status'=>'1'])->sum('r.return_amount');
        // dd($data['totalReturn']);
        return view('admin/main_layout',$data);
    }

    
}
