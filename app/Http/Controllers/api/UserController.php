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
            $where = array('admin_id'=>$request->id);
            $query->where($where);
        }
        
        $query->where('deleted_at',null);
        $query->orderBy('id','desc');
        $data = $query->get();
        foreach($data as $value){
            $value->role = '2';
            $value->is_kyc = '0';
            $kyc = DB::table('user_kyc')->where('user_id',$value->id)->get();
            if(!empty($kyc->all())){
                $value->is_kyc = '1';
                foreach ($kyc as $k => $v) {
                    // $value->name_document = $kyc[0]->name_document;
                    // $value->valid_from = $kyc[0]->valid_from;
                    // $value->valid_thru = $kyc[0]->valid_thru;
                    $v->document_file = url('uploads/kyc_document/'.$v->document_file);
                }
            }
            $value->kyc_doc = $kyc; 

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
        // echo '1';die;
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => ['required', Rule::unique('users')->whereNull('deleted_at')],
            'mobile' => 'required|numeric',
            'country_code' => 'required',
            'dob' => 'required|date_format:"Y-m-d"',
            'status' => 'required',
            'country_id'=>'required',
            'nationality' => 'required',
            'national_id' => 'required',
            'date_of_expiry' => 'required|date_format:"Y-m-d"'
        ]);
        // if(isset($request->is_kyc) && $request->is_kyc == '1'){
        // //  echo '1';die;
        //     $validator = Validator::make($request->all(), [
        //         'name_document.*' => 'required',
        //         'document_file.*' => 'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf',
        //         'valid_from.*' => 'required|date_format:"Y-m-d"',
        //         'valid_thru.*' => 'required|date_format:"Y-m-d"'
        //     ]);
        //     if ($validator->fails()) {
        //         $responce = [
        //             'status'=>'0',
        //             'errors'=>$validator->errors()
        //         ];
        //         return response()->json($responce);
        //     }
        // }
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        // dd($request->all());

            $res = new User();
            $res->admin_id = $request->admin_id;
            $res->fname = $request->fname; 
            $res->lname = $request->lname; 
            $res->email = $request->email; 
            $res->password = bcrypt(123456); 
            $res->gender = $request->gender; 
            $res->mobile = $request->mobile;
            $res->country_code = $request->country_code;
            $res->status = $request->status; 
            $res->country_id = $request->country_id; 
            $res->nationality = $request->nationality;
            $res->national_id = $request->national_id;
            $res->date_of_expiry = $request->date_of_expiry;
            $res->dob = dbDateFormat($request->dob,true); 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            $last_id = $res->id;
            if(isset($request->is_kyc) && $request->is_kyc == "1"){
                // echo '1';die;
                $validator = Validator::make($request->all(), [
                    'name_document.*' => 'required',
                    'document_file.*' => 'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf',
                    'valid_from.*' => 'required|date_format:"Y-m-d"',
                    'valid_thru.*' => 'required|date_format:"Y-m-d"'
                ]);
                if ($validator->fails()) {
                    $responce = [
                        'status'=>'0',
                        'errors'=>$validator->errors()
                    ];
                    return response()->json($responce);
                }
                $filename= '';
                for($key = 0 ; $key <= (count($request->name_document))-1; $key++) {        
                    if($request->hasfile('document_file')){
                        $file = $request->file('document_file')[$key];
                        $ext = $file->getClientOriginalExtension();
                        $filename = 'document_file_'.time().'.'.$ext;
                        $file->move(public_path('uploads/kyc_document'),$filename);
                    }  
                    // $image_path = $request->file('nationalIdImage')->store('kycPicture', 'public');
                        $valid_from = $request->valid_from[$key];
                        $valid_thru = $request->valid_thru[$key];
                        DB::table('user_kyc')->insert([
                            'user_id'=>$last_id,
                            'name_document'=> $request->name_document[$key],
                            'valid_from'=> ( !is_null ($valid_from) ) ? dbDateFormat($valid_from,true) : NULL,
                            'valid_thru'=> ( !is_null ($valid_thru) ) ? dbDateFormat($valid_thru,true) : NULL,
                            'document_file'=>$filename,
                            'created_at' => dbDateFormat(),
                            'updated_at' => dbDateFormat()
                        ]);
                }
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
            $userKyc = DB::table('user_kyc')->where('user_id',$value->id,'deleted_at',NULL)->get();
            if(!empty($userKyc->all())){
                $value->is_kyc = '1';
                foreach ($userKyc as $key => $value) {
                    $value->document_file = url('public/national_id/'.$value->document_file);
                }
                $data[0]->kycData = $userKyc;
            }else{
                $value->is_kyc = '0';
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required',
            'fname'  => 'required',
            'lname'  => 'required',
            'gender' => 'required',
            'mobile' => 'required|numeric',
            'country_code' => 'required',
            'dob'    => 'required|date_format:"Y-m-d"',
            'country_id'=>'required',
            'nationality' => 'required',
            'national_id' => 'required',
            'date_of_expiry' => 'required|date_format:"Y-m-d"'
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
            $res->country_code = $request->country_code;
            if(isset($request->status)){
                $res->status = $request->status; 
            } 
            $res->national_id = $request->national_id;
            $res->date_of_expiry = $request->date_of_expiry;
            $res->dob = dbDateFormat($request->dob,true); 
            $res->updated_at = dbDateFormat(); 
            $result = $res->save(); 
            
            $userKcy = DB::table('user_kyc')->where('user_id',$request->user_id)->get();

                if(isset($request->is_kyc) && $request->is_kyc == "1" ){
                    DB::table('user_kyc')->where('user_id',$request->user_id)->delete();

                    for($key = 0 ; $key <= (count($request->name_document))-1; $key++) {

                        $filename = (isset($req->document_file_exist[$key])) ? $req->document_file_exist[$key] : ''; 
                        $j = 0;
                        if($request->hasfile('document_file') &&  $filename == ''){
                            $file = $request->file('document_file')[$j];
                            $ext = $file->getClientOriginalExtension();
                            $filename = 'document_file_'.time().'.'.$ext;
                            $file->move(public_path('uploads/kyc_document'),$filename);
                            $j++;
                        }
                            $valid_from = $request->valid_from[$key];
                            $valid_thru = $request->valid_thru[$key];
                        
                            DB::table('user_kyc')->insert([
                                'user_id'=>$request->user_id,
                                'name_document'=> $request->name_document[$key],
                                'valid_from'=> ( !is_null ($valid_from) ) ? dbDateFormat($valid_from,true) : NULL,
                                'valid_thru'=> ( !is_null ($valid_thru) ) ? dbDateFormat($valid_thru,true) : NULL,
                                'document_file'=>$filename,
                                'created_at' => dbDateFormat(),
                                'updated_at' => dbDateFormat()
                            ]);
                    }
                }else{
                    $kycData = DB::table('user_kyc')->where('user_id', $request->user_id)->get();
                    for($key = 0 ; $key <= (count($kycData))-1; $key++) {
                        if(file_exists(public_path('uploads/kyc_document/'.$kycData[$key]->document_file))){
                            unlink(public_path('uploads/kyc_document/'.$kycData[$key]->document_file));
                        }
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
