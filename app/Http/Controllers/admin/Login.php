<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Admin;
use App\Models\admin\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;
use Carbon\Carbon; 
use Illuminate\Support\Str;
class Login extends Controller
{

    function index(request $req){
        if(Session::has('admin_login')){
            return Redirect::to(URL::to('/'));
        }
        $req->validate([
            'email' => 'required|email',
            'password' =>'required',
        ], [
            'name.required' => 'Email is required',
            'password.required' => 'password is required',
        ]);
        $Admin_model = new Admin;
        $data = $Admin_model->checkLogin($req->all());
        // dd($data);/
        if(count($data) > 0){

            if(Hash::check($req['password'], $data[0]->password) ){
                $sessionArray = [
                'id'=>$data[0]->id,
                "email"=>$data[0]->email,
                'fname'=>$data[0]->fname,
                'lname'=>$data[0]->lname,
                'super_admin'=>$data[0]->role,
                'role'=>$data[0]->role, // 1=>super_admin // 0=>sales person; 3=>finance person
            ];
                $req->session()->put(['admin_login'=> $sessionArray]);
                // $insertData = ['title'=>'Login','description'=>'Sales person Login','created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                // $this->insertNotification($insertData);
                return Redirect::to(URL::to('/dashboard'));
            }
        }else{
            $email = $req->email;
            
            $user = User::where(['email'=>$email])->get();
            if(count($user) > 0){
                    if($user[0]->status == '1'){
                        if(Hash::check($req['password'], $user[0]->password)){
                            $sessionArray = [
                            'id'=>$user[0]->id,
                            "email"=>$user[0]->email,
                            'fname'=>$user[0]->fname,
                            'lname'=>$user[0]->lname,
                            'user'=>true,
                            'role'=>'2', // 1=>super_admin // 0=>sales person // 2=>customer/users
                        ];
                        $req->session()->put(['admin_login'=> $sessionArray]);
                        // $insertData = ['title'=>'Login','description'=>'Customer login','created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                        // $this->insertNotification($insertData);
                        return Redirect::to(URL::to('/userDashboard'));
                    }
                }else{
                    return Redirect::to(URL::to('/'))->with('Mymessage', flashMessage('danger','Your account is inactive..! contact to admin'));
                }
            }
        }
        return Redirect::to(URL::to('/'))->with('Mymessage', flashMessage('danger','You have entered invalid password'));
    }

    function logout(Request $request){ 
        if(Session::has('admin_login')){
            $request->session()->flush('admin_login');
            return Redirect::to(URL::to('/'));
        }
    }

    public function forgetPassword(Request $request){
        $data['action'] = URL::to('forgetPassword');
        $data['js'] = array('login');
        if($request->all()){
            // echo '1';die;
            $request->validate([
                'email'=>'required|email'
            ]);
            // dd($request->all());

           $record = Admin :: where('email',$request->email)->get();

           if(count($record) > 0){
            $token = Str::random(64);

            // DB::table('password_resets')->insert([
            //     'email' => $request->email, 
            //     'token' => $token, 
            //     'created_at' => Carbon::now()
            //   ]);

              Mail::send('admin.email_template.forgetPassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            return redirect('/forgetPassword')->with('Mymessage', flashMessage('success','We have e-mailed your password reset link!'));
           }else{
            $record = User :: where('email',$request->email)->get();
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
              ]);
            //   return View::Make('admin.email_template.forgetPassword',['token' => $token]);
            Mail::send('admin.email_template.forgetPassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            return redirect('/forgetPassword')->with('Mymessage', flashMessage('success','We have e-mailed your password reset link!'));

           }
        return redirect('/forgetPassword')->with('Mymessage', flashMessage('danger','Something Went Wrong! Try again'));
        }

        return view('admin.forget_password',$data);
    }

    public function showResetPasswordForm($token) { 
        // echo $token; die;
        $data['js'] = ['validateFile'];
        $data['token'] = $token;
        return view('admin.reset_password_link', $data);
     }

     public function submitResetPasswordForm(Request $request)
     {

        $request->validate([
             'email' => 'required',
             'password' => 'required_with:confirm_password|same:confirm_password',
             'confirm_password' => 'required'
            ]
        );
        $updatePassword = DB::table('password_resets')->where([
            'email' => $request->email, 
            'token' => $request->token
            ])
            ->first();
            dd($updatePassword);
        if($updatePassword){
            $is_available = Admin :: where('email',$request->email)->get();
            if(count($is_available) > 0){
                $status = Admin::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);
            }else{
                $status = User::where('email',$request->email)->update(['password' => Hash::make($request->password)]);
            }
            if($status){
                DB::table('password_resets')->where(['email'=> $request->email])->delete(); 
                return redirect('/')->with('Mymessage', flashMessage('success','Your password has been changed!'));
            }else{
                return redirect('/')->with('Mymessage', flashMessage('danger','Something Went wrong'));
            }
        }else{
            return redirect('/reset-password/'.$request->token)->with('Mymessage', flashMessage('danger','Invalid email or token'));
        }
     }


}
