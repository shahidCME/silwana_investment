<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use DB;

class User extends Authenticatable
{
    use HasFactory,SoftDeletes,HasApiTokens;
    public function getData($id){
        $get = User::where(['id'=>$id])->get();
        return $get;
    }

    public function updateRecords($postData){
        // dd($postData);
        $id = decrypt($postData['update_id']);
        $updateData = [
            'name' => $postData['name'],
            'email'=> $postData['email'],
            'mobile'=> $postData['mobile'],
            'gender' => $postData['gender'],
            'dob' => $postData['dob'],
            'status'=>$postData['status'],
            'updated_at' => dbDateFormat(), 
        ];
        User::where('id', $id)->update($updateData);
        return true;
    }
}
