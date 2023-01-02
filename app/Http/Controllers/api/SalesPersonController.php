<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; 
use DB;
// use SoftDeletes;
class SalesPersonController extends Controller
{
    public function index(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'role'=>'required'
        // ]);
        // if ($validator->fails()) {
        //     $responce = [
        //         'status'=>'0',
        //         'errors'=>$validator->errors()
        //     ];
        //     return response()->json($responce);
        // }

        if($request->role == '3'){
            $message = 'Finance person list';
        
        }elseif($request->role == '4'){

            $message = 'Approver list';
        
        }else{
            $message = 'Sales person list';
        }

        $limit = 10;
        $offset = 0;
        $query = DB::table('admins')->select('id','email','fname','lname','mobile','role','status','created_at','updated_at');
        $query->where(['role'=>$request->role,'deleted_at'=>null]);
        if($request['offset'] > 0 ){
            $off= $limit * $request['offset'];
            $query->skip($off);
        }
        $query->take($limit);
        // Elq();
        $query->orderBy('id','desc');
        $salesPerson = $query->get();
        // Plq();
        $responce = [
            'status'=>'1',
            'message'=>$message,
            'data'=>$salesPerson,
        ];
        return response()->json($responce);
        // dd($salesPerson);

    }

    public function addSalesPerson(Request $request){
         $validator = Validator::make($request->all(), [
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required|email|unique:admins',
            'mobile'=>'required',
            'role'=>'required',
            'status'=>'required',
            'country_code'=>'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        // dd($request->all());
        $insertData = [
            'fname' => $request['fname'],
            'lname' => $request['lname'],
            'email'=> $request['email'],
            'password'=> bcrypt(123456),
            'country_code'=> $request['country_code'],
            'mobile'=> $request['mobile'],
            'role' => $request['role'],
            'status' => $request['status'],
            'created_at' => dbDateFormat(),
            'updated_at' => dbDateFormat(),
        ];
        $sta = DB :: table('admins')->insert($insertData);
        if($sta){
            $responce = [
                'status'=>'1',
                'message'=>'Record added successfully',
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'Somthing went wrong'
            ];
        }
        return response()->json($responce);
    }

    public function editSalesPerson(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $get = DB::table('admins')->select('fname','lname','email','mobile','role','status','created_at','updated_at')->where(['id'=>$request->id])->get();
        $responce = [
            'status'=>'1',
            'message'=>'Editable Record',
            'data'=>$get
        ];
        return response()->json($responce);
    }

    public function updateRecord(Request $request){
        $validator = Validator::make($request->all(), [
            'fname'=>'required',
            'lname'=>'required',
            'email' => ['required','email',
                        Rule::unique('admins')->ignore($request->id),
                    ],
            'mobile'=>'required',
            'role'=>'required',
            'status'=>'required',
            'country_code'=>'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        if($request ->all()){
            $id = $request['id'];
            $updateData = [
                'fname' => $request['fname'],
                'lname' => $request['lname'],
                'email'=> $request['email'],
                'country_code'=> $request['country_code'],
                'mobile'=> $request['mobile'],
                'role' => $request['role'],
                'status' => $request['status'],
                'updated_at' => dbDateFormat(), 
            ];
           $res = DB::table('admins')->where('id', $id)->update($updateData);
            if($res){
                $responce = [
                    'status'=>'1',
                    'message'=>"Record updated successfully"
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>"Somthing Went Wrong"
                ];
            }
            return response()->json($responce);
        }
    }

    public function deleteRecord(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $request->id;
        $is_avail = Admin::where('id',$id)->first();
        if($is_avail != null){
            $res = Admin::find($id)->delete();
            if($res){
                $responce = [
                    'status'=>'1',
                    'message'=>'Record deleted successfully',
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>'Somthing Wend Wrong'
                ];
            }
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'No record available to delete'
            ];
        }
        return response()->json($responce);
    }
}
