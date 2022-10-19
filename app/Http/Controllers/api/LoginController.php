<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Admin;
use DB;
class LoginController extends Controller
{
    
    public function index(Request $request){
        dd($request->email);
        
    }
}
