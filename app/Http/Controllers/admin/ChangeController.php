<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Admin;
use App\Models\admin\User;
use Illuminate\Support\Facades\Hash;
use DB;
class ChangeController extends Controller
{
    public function index(Request $req){
        $data['page'] = 'admin.profile';
        $data['title'] = 'Profile';
        $data['js'] = ['validateFile'];
        $data['action'] = url('/profile');
        $data['cancelBtn'] = url('/dashboard');
        $sessionData = admin_login();
        $data['profileData'] = Admin::select('fname','lname','mobile','country_code')->where('id',$sessionData['id'])->get();
        if($req->all()){
            // dd($req['fname']);
            $willUpdate = ['fname'=>$req['fname'],'lname'=>$req['lname'],'mobile'=>$req['mobile'],'country_code'=>$req['country_code']]; 
            $updateStatus = Admin::where('id',$sessionData['id'])->update($willUpdate);
            if($updateStatus){
                $d = session()->get('admin_login');
                session()->put('admin_login.fname',$req['fname']);
                return redirect('profile')->with('Mymessage', flashMessage('success','Profile Updated Successfully'));
            }else{
                return redirect('profile')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin.main_layout',$data);

    }

    public function userProfile(Request $req){
        $data['page'] = 'admin.user_profile';
        $data['title'] = 'Profile';
        $data['js'] = ['validateFile'];
        $data['action'] = url('/userProfile');
        $data['cancelBtn'] = url('/userDashboard');
        $sessionData = admin_login();
        $data['profileData'] = User::select('fname','lname','mobile','gender','dob','country_code')->where('id',$sessionData['id'])->get();
        // dd($data['profileData']->all());
        if($req->all()){
            $willUpdate = [
                'fname'=>$req['fname'],
                'lname'=>$req['lname'],
                'dob'=>dbDateFormat($req['dob'],true),
                'mobile'=>$req['mobile'],
                'gender'=>$req['gender'],
                'country_code'=>$req['country_code']
            ]; 
            $updateStatus = User::where('id',$sessionData['id'])->update($willUpdate);
            if($updateStatus){
                $d = session()->get('admin_login');
                session()->put('admin_login.fname',$req['fname']);
                return redirect('userProfile')->with('Mymessage', flashMessage('success','Profile Updated Successfully'));
            }else{
                return redirect('userProfile')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin.main_layout',$data);

    }

    public function changePassword(Request $req){
        $data['page'] = 'admin.change_password';
        $data['js'] = ['validateFile'];
        $data['title'] = 'Change Password'; 
        $data['action'] = url('/changePassword');
        $data['cancelBtn'] = url('/dashboard');
            if($req->all()){
                $sessionData = admin_login();
                if($sessionData['role'] != '2'){
                    $adminData = Admin::where('id',$sessionData['id'])->get();
                    if(Hash::check($req['old_password'], $adminData[0]->password) ){ 
                        $willUpdate = ['password'=>bcrypt($req->password),'updated_at'=>dbDateFormat()];
                        $updateStatus = Admin::where('id',$sessionData['id'])->update($willUpdate);
                        }else{
                            return redirect('changePassword')->with('Mymessage', flashMessage('danger','I have entered wrong old password'));  
                        }
                }else{
                    $userData = User::where('id',$sessionData['id'])->get();
                        if(Hash::check($req['old_password'], $userData[0]->password) ){ 
                            $willUpdate = ['password'=>bcrypt($req->password),'updated_at'=>dbDateFormat()];
                            $updateStatus = User::where('id',$sessionData['id'])->update($willUpdate);
                        }else{
                            return redirect('changePassword')->with('Mymessage', flashMessage('danger','I have entered wrong old password'));  
                        }
                }
                if($updateStatus){
                        return redirect('changePassword')->with('Mymessage', flashMessage('success','Password Updated Successfully'));
                }else{
                            return redirect('changePassword')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
                }
        }
        return view('admin.main_layout',$data);
    }

}
