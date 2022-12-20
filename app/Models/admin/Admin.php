<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasApiTokens,HasFactory,SoftDeletes;

    
    protected $fillable = [
        'name', 'email','password'
    ];

    function checkLogin($post){
        $email = $post['email'];
        $get = Admin::where(['email'=>$email,'status'=>'1'])->get();
        return $get;
    }

    function getAdmin($id =''){
        $get = Admin::where(['id'=>$id,'status'=>'1'])->get();
        return $get;
    }

    public function  insertRecords($postData){
        $insertData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> $postData['email'],
            'password'=> bcrypt($postData['password']),
            'mobile'=> $postData['mobile'],
            'country_code'=> $postData['country_code'],
            'role' => $postData['role'],
            'status' => $postData['status'],
            'created_at' => dbDateFormat(),
            'updated_at' => dbDateFormat(),
        ];
       return DB :: table('admins')->insert($insertData);
    }

    public function getData($id){
        $get = Admin::where(['id'=>$id])->get();
        return $get;
    }

    public function updateRecords($postData){
        // dd($postData);
        $id = decrypt($postData['update_id']);
        $updateData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> $postData['email'],
            'country_code'=> $postData['country_code'],
            'mobile'=> $postData['mobile'],
            'role' => $postData['role'],
            'status' => $postData['status'],
            'updated_at' => dbDateFormat(), 
        ];
        Admin::where('id', $id)
        ->update($updateData);
        return true;
    }

    public function deleteRecord($id){
        return DB::table('admins')->where('id', $id)->delete();
    }

    public function  addFinancePerson($postData){
        $insertData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> $postData['email'],
            'password'=> bcrypt($postData['password']),
            'country_code'=> $postData['country_code'],
            'mobile'=> $postData['mobile'],
            'role' => '3',
            'status' => $postData['status'],
            'created_at' => dbDateFormat(),
            'updated_at' => dbDateFormat(),
        ];
       return DB :: table('admins')->insert($insertData);
    }

    public function updateFinanceRecords($postData){
        $id = decrypt($postData['update_id']);
        $updateData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> $postData['email'],
            'country_code'=> $postData['country_code'],
            'mobile'=> $postData['mobile'],
            'status' => $postData['status'],
            'updated_at' => dbDateFormat(), 
        ];
        Admin::where('id', $id)
        ->update($updateData);
        return true;
    }

    
}

