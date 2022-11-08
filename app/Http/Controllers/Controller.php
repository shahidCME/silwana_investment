<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\admin\User;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function __construct(){
        $re = User::all();
    }

    function insertNotification($insertData){
        if(admin_login()['role'] == '0'){
            $insertData['admin_id'] = admin_login()['id'];
        }
        if(admin_login()['role'] == '2'){
            $insertData['user_id'] = admin_login()['id'];
        }
        DB::table('notifications')->insert($insertData);
        return true;
    }


}
