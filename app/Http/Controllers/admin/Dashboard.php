<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\admin\{Investment,Admin,User,Schema};
use DB;
class Dashboard extends Controller
{
    
    function index(){
       $totalInvestment = Investment::where('status','1');
        if(admin_login()['role'] == '2'  ){
            $totalInvestment->where('admin_id',admin_login()['id']);
        }
        // Elq();
        $totalInvestment =  $totalInvestment->sum('amount');
        // Plq();
        $data['totalInvestment'] = $totalInvestment;

        $user = DB :: table('users')->where('deleted_at',NULL);
        if(admin_login()['role'] != '1' ){
            $user->where('admin_id',admin_login()['id']);
        }
        $totalUser =  $user->count();
        $data['totalUser'] = $totalUser;


        $data['totalSalePerson'] = Admin::where('role','0')->count();
        $data['totalFinancePerson'] = Admin::where('role','3')->count();
        $data['totalApprover'] = Admin::where('role','4')->count();
        $data['totalSchema'] = Schema::count();

        $queryCancelled = Investment::where('status','9');
        if(admin_login()['role'] == '2' ){
            $queryCancelled->where('admin_id',admin_login()['id']);
        }
        // Elq();
        $totalCancelledInvestment = $queryCancelled->count();
        // Plq();
        $data['totalCancelledInvestment'] = $totalCancelledInvestment;
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
