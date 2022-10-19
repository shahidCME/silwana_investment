<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;
class Admin extends Model
{
    use HasFactory;
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
        $id = decrypt($postData['update_id']);
        $updateData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> $postData['email'],
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
}

