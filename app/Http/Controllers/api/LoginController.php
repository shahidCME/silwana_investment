<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\admin\Admin;
use App\Models\admin\User;
use App\Models\Device;
use DB;
use Mail;
use Carbon\Carbon; 
use Illuminate\Support\Str;
class LoginController extends Controller
{
    
    public function index(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required'
        ]);
         // Check email
         $user = User::where('email', $fields['email'])->first();
         if($user != null){
            Device :: where(['role'=>'2','user_id'=>$user->id])->delete();
            $deviceData = [
                'user_id'=>$user->id,
                'device_id'=>$request->device_id,
                'token'=>(isset($request->token) && $request->token != '') ? $request->token : NULL,
                'role'=>2,
                'type'=>$request->type,
                'created_at'=>dbDateFormat(),
                'updated_at'=>dbDateFormat(),
            ];
            Device::create($deviceData);
            if($user->status == '1'){
                if(Hash::check($fields['password'], $user->password)) {
                    // $token = $user->createToken('token',['user'])->plainTextToken;
                    $response = [
                        'status' => '1',
                        'message' => 'Login successfull',
                        'role' =>'2',
                        'id' =>$user->id
                    ];
                    return response()->json($response);
                }
            }else{
                $response = [
                    'status' => '0',
                    'message' => 'Your account is inactive..! contact to admin',
                ];
                return response()->json($response);
            }
        }else{
            $admin = Admin::where('email', $fields['email'])->first();
            Device :: where(['role'=>'1','user_id'=>$admin->id])->delete();
            $deviceData = [
                'user_id'=>$admin->id,
                'device_id'=>$request->device_id,
                'token'=>(isset($request->token) && $request->token != '') ? $request->token : NULL,
                'role'=>1,
                'type'=>$request->type,
                'created_at'=>dbDateFormat(),
                'updated_at'=>dbDateFormat(),
            ];
            Device::create($deviceData);
            if($admin != null){
                if(Hash::check($fields['password'], $admin->password)) {
                    // $token = $admin->createToken('token',['admin'])->plainTextToken;
                    $response = [
                        'status' => '1',
                        'message' => 'Login successfull',
                        'role' => $admin->role,
                        'id'=>$admin->id
                    ];
                    // echo '1';die;
                    // $notification_id = "dXMijcsNQbae8B8m9YKBa4:APA91bEs9me3vSJNevAiG8jAm2gzlX7DK06dAHAzxuzaeYqgV_tUZtfpZlq6wnY2YzYMIKdpg_jx9sC83SuzvboF1BtZrM8UgZZEj-hhx0zDTMLE9Ay5Y0IRFuPrFxVZVurjWe3YkUHH";
                    // $title = "Greeting Notification";
                    // $message = "Have good day!";
                    // $id = '1';
                    // $type = "a";
                    
                    // $res = send_notification_FCM($notification_id, $title, $message, $id,$type);

                     return response()->json($response);
                }

             }

        }
        
        $response = [
            'status'=>'0',
            'message'=>'Invalid Email and password'
        ];

           
        return response()->json($response);
         

        
    }

    public function logout() {
        return [
            'status'=>'1',
            'message' => 'Logged out successefully'
        ];
    }

    public function forgetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        
        $record = User :: where('email',$request->email)->get();
        if(empty($record->all())){
            $record = Admin :: where('email',$request->email)->get();
        }

        if(!empty($record->all())){
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
              ]);

            Mail::send('admin.email_template.forgetPassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            $responce = [
                'status'=>'1',
                'message'=>"We have e-mailed your password reset link!"
            ];
        }else{  
            $responce = [
                'status'=>'0',
                'message'=>"Invalid email"
            ];
           }
        return response()->json($responce);
    }

    public function getProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required|numeric',
            'is_user'=>'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        if($request->is_user == '0'){ 
            $profile = Admin::select('fname','lname','mobile','country_code')->where('id',$request['id'])->get();
        }else{
            $profile = User::select('fname','lname','mobile','gender','dob','country_code')->where('id',$request['id'])->get();
        }
        if(!empty($profile->all())){
            $responce = [
                'status'=>'1',
                'message'=>'Profile data',
                'data' =>$profile
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'Somthing went wrong'
            ];
        }
        return response()->json($responce);

    }

    public function updateProfile(Request $request){
        
        if($request->is_user == '0'){
                $validator = Validator::make($request->all(), [
                'id'=>'required|numeric',
                'fname'=>'required',
                'lname'=>'required',
                'country_code'=>'required',
                'mobile'=>'required',
            ]);
          
        }else{
            $validator = Validator::make($request->all(), [
                'id'=>'required|numeric',
                'fname'=>'required',
                'lname'=>'required',
                'dob'=>'required|date_format:"Y-m-d"',
                'country_code'=>'required',
                'mobile'=>'required',
                'gender'=>'required',
            ]);
          
            
        }
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $updateStatus = false;
        if($request->is_user == '0'){
            $willUpdate = [
                'fname'=>$request['fname'],
                'lname'=>$request['lname'],
                'country_code'=>$request['country_code'],
                'mobile'=>$request['mobile'],
                'updated_at'=>dbDateFormat()
            ]; 
            $updateStatus = Admin::where('id',$request['id'])->update($willUpdate);
        }else{
            $willUpdate = [
                'fname'=>$request['fname'],
                'lname'=>$request['lname'],
                'dob'=>dbDateFormat($request['dob'],true),
                'country_code'=>$request['country_code'],
                'mobile'=>$request['mobile'],
                'gender'=>$request['gender'],
                'updated_at'=>dbDateFormat()
                
            ]; 
            $updateStatus = User::where('id',$request['id'])->update($willUpdate);
        }
        if($updateStatus){
            $responce = [
                'status'=>'1',
                'message'=>'Profile updated successfully'
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'Somthing went wrong'
            ];
        }
        return response()->json($responce);
    }

        public function changePassword(Request $req){
            $validator = Validator::make($req->all(), [
                'user_id'=>'required',
                'role'=>'required',
                'old_password'=>'required',
                'new_password'=>'required',
                'confirm_password' => 'required_with:new_password|same:new_password'
            ]);
            if ($validator->fails()) {
                $responce = [
                    'status'=>'0',
                    'errors'=>$validator->errors()
                ];
                return response()->json($responce);
            }
            if($req->role != '2'){
                $adminData = Admin::where('id',$req->user_id)->get();
                if(Hash::check($req['old_password'], $adminData[0]->password) ){ 
                    $willUpdate = ['password'=>bcrypt($req->password),'updated_at'=>dbDateFormat()];
                    $updateStatus = Admin::where('id',$req->user_id)->update($willUpdate);
                    }else{
                        $responce = [
                            'status'=>'0',
                            'message'=>'You have entered wrong old password'
                        ];
                    }
            }else{
                $userData = User::where('id',$req->user_id)->get();
                    if(Hash::check($req['old_password'], $userData[0]->password) ){ 
                        $willUpdate = ['password'=>bcrypt($req->password),'updated_at'=>dbDateFormat()];
                        $updateStatus = User::where('id',$req->user_id)->update($willUpdate);
                    }else{
                        $responce = [
                            'status'=>'0',
                            'message'=>'You have entered wrong old password'
                        ];
                    }
            }
                if($updateStatus){
                    $responce = [
                        'status'=>'1',
                        'message'=>'Password Updated Successfully'
                    ];
                }else{
                    $responce = [
                        'status'=>'0',
                        'message'=>'Somthing went wrong'
                    ];
                }
            return response()->json($responce);
        }
}
