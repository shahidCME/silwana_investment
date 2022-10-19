<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Admin;
use Closure;
use DB;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if(!Session::has('admin_login')){
            return Redirect::to(URL::to('/'));
        }
        $data = Session::get('admin_login');
        if($data['role'] == "2" ){
            return Redirect::to(URL::to('/userDashboard'));
            // return Redirect::to(URL::to('/userDashboard'))->with('Mymessage', flashMessage('danger','You are not autherised to access this route'));
        }
        return $next($request);
    }
}
