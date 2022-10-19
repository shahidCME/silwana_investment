<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Investment extends Model
{
    use HasFactory,SoftDeletes;

    public function getData($id){
       return  Investment::where('id','=',$id)->get();
    }

    // public function getCustomer(){
    //     $query = DB :: table('users');
    //     $query->where('status','=','1');
    //     $query->where('deleted_at','=',null);
    //     $res = $query->get();
    //     return $res;
    // }

    // public function getSchema(){
    //     $query = DB :: table('schemas');
    //     $query->where('status','=','1');
    //     $query->where('deleted_at','=',null);
    //     $res = $query->get();
    //     return $res;
    // }


    public function updateRecords($postData,$filename,$invest_document,$other_document){
        // dd($postData);
        $id = decrypt($postData['update_id']);
        $updateData = [
            'user_id' => $postData['customer'],
            'schema_id'=> $postData['schema'],
            'tenure'=> $postData['tenure'],
            'start_date'=> dbDateFormat($postData['start_date'],true),
            'amount' => $postData['amount'],
            'return_type' => $postData['return_type'],
            'return_percentage' => $postData['return_percentage'],
            'status' => (isset($postData['status'])) ? $postData['status'] : '2',
            'contract_reciept' => $filename,
            'investment_doc' => $invest_document,
            'other_doc' => $other_document,
            'updated_at' => dbDateFormat(), 
        ];
        Investment::where('id', $id)->update($updateData);
        DB::table('roi')->where(['investment_id' =>$id,'status'=>'0'])->delete(); 

        $roi = DB::table('roi')->where(['investment_id' =>$id,'status'=>'1'])->get()->toArray();
        $completedRecord = count($roi);
        for ($i=1; $i <= ($postData['tenure']-$completedRecord)  ; $i++) { 
            if(!empty($roi)){
                $date_of_return = $roi[$completedRecord-1]->date_of_return;
                $continueFrom = strtotime($date_of_return);
            }else{
                $continueFrom = strtotime(dbDateFormat($postData['start_date'],true));
            }
            if($postData['return_type'] == '0'){
                $final_date = date("Y-m-d", strtotime("+".$i." month", $continueFrom));
            }else{
                $final_date = date('Y-m-d', strtotime("+".$i." years",$continueFrom));
            }
            // dd($final_date);
            $returnAmount = ($postData['amount'] * $postData['return_percentage'])/100;
            $willInsert = [
                'investment_id'=>$id,
                'return_amount'=>$returnAmount,
                'date_of_return'=> $final_date,
                'status'=>'0',
                'return_percentage'=>$postData['return_percentage'],
                'investment_amount'=>$postData['amount'],
                'created_at' => dbDateFormat(),
                'updated_at' => dbDateFormat() 
            ];
            DB::table('roi')->insert($willInsert);
        }
        return true;
    }
}
