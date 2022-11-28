<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\User;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Validator;

use DB;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $query = DB::table('users');
        if($request->role == '0'){
            $where = array('admin_id',$request->id);
            $query->where($where);
        }
        
        $query->where('deleted_at',null);
        $query->orderBy('id','desc');
        $data = $query->get();
        foreach($data as $value){
            $value->role = '2';
            $data[0]->is_kyc = '0';
            $kyc = DB::table('user_kyc')->where('user_id',$value->id)->get();
            if(!empty($kyc->all())){
                $value->is_kyc = '1';
                $value->nationalIdImage = url('public/national_id/'.$kyc[0]->nationalIdImage);
                $value->national_id = $kyc[0]->national_id;
                $value->address = $kyc[0]->address;
                $value->date_of_expiry = $kyc[0]->date_of_expiry;
            }
        }
        $responce = [
            'status' => '1',
            'message'=> 'user list',
            'data' => $data
        ];
        return response()->json($responce);

    }

    public function getCountryCode(){
        $data = DB::table('countries')->get();
        $responce = [
            'status' => '1',
            'message'=> 'country code',
            'data' => $data
        ];
        return response()->json($responce);
    }

    public function addUser(Request $request){
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|numeric',
            'dob' => 'required|date_format:"Y-m-d"',
            'status' => 'required',
            'country_id'=>'required',
            'nationality' => 'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
            $res = new User();
            $res->admin_id = $request->admin_id;
            $res->fname = $request->fname; 
            $res->lname = $request->lname; 
            $res->email = $request->email; 
            $res->password = bcrypt(123456); 
            $res->gender = $request->gender; 
            $res->mobile = $request->mobile; 
            $res->status = $request->status; 
            $res->country_id = $request->country_id; 
            $res->nationality = $request->nationality;
            $res->dob = dbDateFormat($request->dob,true); 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            $last_id = $res->id;
            if(isset($request->is_kyc) && $request->is_kyc == 1){
                $validator = Validator::make($request->all(), [
                    'national_id' => 'required',
                    'address' => 'required',
                    'nationalIdImage' => 'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf',
                    'date_of_expiry' => 'required|date_format:"Y-m-d"'
                ]);
                if ($validator->fails()) {
                    $responce = [
                        'status'=>'0',
                        'errors'=>$validator->errors()
                    ];
                    return response()->json($responce);
                }
                $filename= '';
                if($request->hasfile('nationalIdImage')){
                    $file = $request->file('nationalIdImage');
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'national_id_'.time().'.'.$ext;
                    $file->move(public_path('uploads/national_id'),$filename);
                    // $image_path = $request->file('nationalIdImage')->store('kycPicture', 'public');
                }
                DB::table('user_kyc')->insert([
                    'user_id'=>$last_id,
                    'national_id'=> $request->national_id,
                    'address'=>$request->address,
                    "date_of_expiry"=>$request->date_of_expiry,
                    'nationalIdImage'=>$filename,
                    'created_at' => dbDateFormat(),
                    'updated_at' => dbDateFormat()
                ]);
            }
            if ($last_id) {
                $responce = [
                    'status'=>'1',
                    'message'=>'Record inserted successfully'
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>'Somthing went wrong'
                ];  
            }
            return response()->json($responce);

    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        // dd($request->user_id);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }

        $data = User::where('id',$request->user_id)->get();
        foreach ($data as $key => $value) {
            $userKyc = DB::table('user_kyc')->where('user_id',$value->id,'deleted_at',null)->get();
            if(!empty($userKyc->all())){
                $value->is_kyc = true;
                $value->kycData = $userKyc;
                $userKyc[0]->nationalIdImage = url('uploads/national_id/'.$userKyc[0]->nationalIdImage);
            }else{
                $value->is_kyc = false;
            }
        }
        $responce = [
            'status'=>'1',
            'message'=>'edit data',
            'data'=>$data
        ];
        return response()->json($responce);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required',
            'fname'  => 'required',
            'lname'  => 'required',
            'gender' => 'required',
            'mobile' => 'required|numeric',
            'dob'    => 'required|date_format:"Y-m-d"',
            'country_id'=>'required',
            'nationality' => 'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
            $res = User ::find($request->user_id);
            $res->fname = $request->fname; 
            $res->lname = $request->lname; 
            $res->gender = $request->gender; 
            $res->country_id = $request->country_id;
            $res->nationality = $request->nationality;
            $res->mobile = $request->mobile;
            if(isset($request->status)){
                $res->status = $request->status; 
            } 
            $res->dob = dbDateFormat($request->dob,true); 
            $res->updated_at = dbDateFormat(); 
            $result = $res->save(); 
            $userKcy = DB::table('user_kyc')->where('user_id',$request->user_id)->get();
            // dd($userKcy);
            if(isset($request->is_kyc) && $request->is_kyc == 1 ){
                // $filename = $request->old_image;
                if($request->hasfile('nationalIdImage')){
                    if(!empty($userKcy->all()) && file_exists(public_path('uploads/national_id/'.$userKcy[0]->nationalIdImage)) ){
                        unlink(public_path('uploads/national_id/'.$userKcy[0]->nationalIdImage));
                    }
                    $file = $request->file('nationalIdImage');
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'national_id_'.time().'.'.$ext;
                    $file->move(public_path('uploads/national_id'),$filename);
                }
                if(!empty($userKcy->all())){
                    $updateData = [
                        'national_id'=>$request->national_id,
                        'address'=>$request->address,
                        'nationalIdImage'=>(isset($filename) && $filename != '') ? $filename : $userKcy[0]->nationalIdImage,
                        'updated_at' => dbDateFormat()
                    ];
                    DB::table('user_kyc')->where('user_id', $request->user_id)->update($updateData);
                }else{
                    DB::table('user_kyc')->insert([
                        'user_id'=>$request->user_id,
                        'national_id'=> $request->national_id,
                        'address'=>$request->address,
                        "date_of_expiry"=>$request->date_of_expiry,
                        'nationalIdImage'=>(isset($filename)) ? $filename : $userKcy[0]->nationalIdImage ,
                        'created_at'=>dbDateFormat(),
                        'updated_at'=>dbDateFormat()
                    ]);
                }
            }else{
                // dd(decrypt($request->update_id)  );
                if($userKcy[0]->nationalIdImage != '' && file_exists(public_path('uploads/national_id/'.$userKcy[0]->nationalIdImage))){
                    unlink(public_path('uploads/national_id/'.$userKcy[0]->nationalIdImage));
                }
                DB::table('user_kyc')->where('user_id',$request->user_id)->delete(); 
            }
            if($result){
                $responce = [
                    'status'=>'1',
                    'message'=>'Update Record Successfully'
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>'Somthing Went Wrong'
                ];
            }
            return response()->json($responce);
        
        

    }

    function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $request->user_id;
        $res = User::find($id)->delete();
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
        return response()->json($responce);
    }

}
