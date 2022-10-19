<?php
use Illuminate\Support\Facades\Session;

function flashMessage($status,$message)
{
    $message = '<div class="alert alert-'.$status.' alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>'.$message.'</strong>
                </div>';
    return $message;
}

function dbDateFormat($date='',$isOnlyDate = false){
    if($date != '' && $isOnlyDate==false){
        return date('Y-m-d H:i:s',strtotime($date));
    }
    if($date != '' && $isOnlyDate==true){
        return date('Y-m-d',strtotime($date));
    }
    return date('Y-m-d H:i:s');
}

function admin_login(){
    
    return Session::get('admin_login');
}

function Elq(){
    \DB::enableQueryLog(); // Enable query log
} 

function Plq(){
    dd(\DB::getQueryLog());
} 
function getNotification($isLimit=''){
    $query = DB::table('notifications');
    if(admin_login()['role'] == '0'){
        $query->where('admin_id',admin_login()['id']);
    }
    if(admin_login()['role'] == '2'){
        $query->where('user_id',admin_login()['id']);
    }
    if($isLimit){
        $query->limit(10);
    }
    $query->orderBy('id','DESC');
    $data = $query->get();
    return $data;
}


?>