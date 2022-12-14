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
    // protected $hidden = ['password'];
    public function getData($id){
        $get = User::where(['id'=>$id])->get();
        return $get;
    }

    public function updateRecords($postData){
        
        $id = decrypt($postData['update_id']);
        $updateData = [
            'fname' => $postData['fname'],
            'lname' => $postData['lname'],
            'email'=> strtolower($postData['email']),
            'country_code'=> $postData['country_code'],
            'mobile'=> $postData['mobile'],
            'country_id'=> $postData['country'],
            'nationality'=> $postData['nationality'],
            'gender' => $postData['gender'],
            'national_id' => $postData['national_id'], 
            'date_of_expiry' => dbDateFormat($postData['date_of_expiry'],true), 
            'dob' => dbDateFormat($postData['dob'],true),
            'status'=>$postData['status'],
            'updated_at' => dbDateFormat(), 
        ];
        // dd($updateData); 
        User::where('id', $id)->update($updateData);
        return true;
    }
}
