<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    use HasFactory,SoftDeletes;

    public function getData($id){
        $get = Schema::where(['id'=>$id])->get();
        return $get;
    }

    public function updateRecords($postData,$filename,$document){
        // dd($postData);
        $id = decrypt($postData['update_id']);
        $updateData = [
            'name' => $postData['name'],
            'type'=> $postData['type'],
            'start_date'=> dbDateFormat($postData['start_date'],true),
            'status' => $postData['status'],
            'image' => $filename,
            'documents' => $document,
            'details' => $postData['details'],
            'updated_at' => dbDateFormat(), 
        ];
        Schema::where('id', $id)->update($updateData);
        return true;
    }

}
