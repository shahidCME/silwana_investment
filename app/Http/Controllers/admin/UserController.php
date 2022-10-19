<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\File;
use DB;
use DataTables;
use SoftDeletes;
class UserController extends Controller
{
   
    public function index()
    {
        // dd(Session::get('admin_login'));
        $data['page'] = "admin.users.list";
        $data['js'] = ['users'];
        $data['addCustomer'] = url('addCustomer');
        $data['title'] = "Customer list";
        return view('admin/main_layout',$data);
    }
    
    public function getCustomerDataTable(Request $request){
        if ($request->ajax()) {
            $Session = Session::get('admin_login');
            $where = array(['admin_id','=',$Session['id']]);
            $data = User::where($where)->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('first name', function($row){
                    return $row->fname;
                })
                ->addColumn('last name', function($row){
                    return $row->lname;
                })
                ->addColumn('action', function($row){
                    $encryptedId = encrypt($row->id);
                    $editurl = "customerEdit/".$encryptedId;
                    $deleteurl = "customerDelete/".$encryptedId;
                   $btn = '<div class="dropdown">
                   <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                       <i class="dw dw-more"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                       <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                       <a class="dropdown-item" href="'.url($editurl).'"><i class="dw dw-edit2"></i> Edit</a>
                       <a class="dropdown-item deleteRecord" href="'.url($deleteurl).'"><i class="dw dw-delete-3 "></i> Delete</a>
                     
                     
                   </div>
               </div>'; 
                         return $btn;
                })
                ->addColumn('status', function($row){
                    return ($row->status == '1') ? '<button type="button" class="badge badge-success">Active</button>' : '<button type="button" class="badge badge-danger   ">Inactive</button>';
                })
                ->rawColumns(['first name','last name','status','action'])
                ->make(true);
        }

        return view('admin/main_layout');
    }

    public function add(Request $req)
    {
        $data['page'] =  'admin.users.add';
        $data['action'] = url('addCustomer');
        $data['js'] = array('validateFile','users');
        $data['title'] = 'Add Customer';
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required|email|unique:users',
                'mobile' => 'required',
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required'
            ]);


            if(Session::has('admin_login')){
                $sessionValue = Session :: get('admin_login');
            }
            $res = new User();
            $res->admin_id = $sessionValue['id'];
            $res->fname = $req->fname; 
            $res->lname = $req->lname; 
            $res->email = $req->email; 
            $res->password = bcrypt($req->password); 
            $res->gender = $req->gender; 
            $res->mobile = $req->mobile; 
            $res->status = $req->status; 
            $res->dob = dbDateFormat($req->dob,true); 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            $last_id = $res->id;
            if(isset($req->is_kyc)){
                if($req->hasfile('nationalIdImage')){
                    $file = $req->file('nationalIdImage');
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'national_id_'.time().'.'.$ext;
                    $file->move(public_path('uploads/national_id'),$filename);
                    // $image_path = $req->file('nationalIdImage')->store('kycPicture', 'public');
                }
                DB::table('user_kyc')->insert([
                    'user_id'=>$last_id,
                    'national_id'=> $req->national_id,
                    'address'=>$req->address,
                    'nationalIdImage'=>$filename,
                ]);
            }
            if($res){
                return redirect('customer')->with('Mymessage', flashMessage('success','Record Inserted Successfully'));
            }else{
                return redirect('customer')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function edit ($eid='',Request $req){
        if($eid!= ''){
            $id =  decrypt($eid);
            $res =  User::getData($id);
            foreach ($res as $key => $value) {
                $userKyc = DB::table('user_kyc')->where('user_id','=',$value->id)->get();
                if(!empty($userKyc->all())){
                    $value->kyc = true;
                    $value->kycData = $userKyc;
                }else{
                    $value->kyc = false;
                }
            }
            // dd($res[0]->kyc);
            $data['editData'] = $res;
            $data['update_id'] = $eid;
        }
        // dd($res);
        $data['page'] = 'admin.users.edit';
        $data['title'] = 'Eidt Customer ';
        $data['js'] = array('validateFile','users');
        $data['action'] = url('customerEdit');
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => ['required','email',
                        Rule::unique('users')->ignore(decrypt($req->update_id)),
                    ],
                'mobile' => 'required',
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required'
            ]);
            $res = User :: updateRecords($req->all());
            $filename = (isset($req->old_image) && $req->old_image != NULL) ? $req->old_image : '';
            
            if(isset($req->is_kyc)){
                // $filename = $req->old_image;
                if($req->hasfile('editnationalIdImage')){
                    if($filename != NULL && file_exists(public_path('uploads/national_id/'.$filename)) ){
                        unlink(public_path('uploads/national_id/'.$filename));
                    }
                    $file = $req->file('editnationalIdImage');
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'national_id_'.time().'.'.$ext;
                    $file->move(public_path('uploads/national_id'),$filename);
                }
                $user_kyc = DB::table('user_kyc')->where('user_id','=',decrypt($req->update_id))->get();
                if(!empty($user_kyc)){
                    $updateData = [
                        'national_id'=>$req->national_id,
                        'address'=>$req->address,
                        'nationalIdImage'=>$filename,
                        'updated_at' => dbDateFormat()
                    ];
                    DB::table('user_kyc')->where('user_id', decrypt($req->update_id))->update($updateData);
                }
                DB::table('user_kyc')->insert([
                    'user_id'=>decrypt($req->update_id),
                    'national_id'=> $req->national_id,
                    'address'=>$req->address,
                    'nationalIdImage'=>$filename,
                    'created_at'=>dbDateFormat(),
                    'updated_at'=>dbDateFormat()
                ]);
            }else{
                // dd(decrypt($req->update_id)  );
                if($filename != '' && file_exists(public_path('uploads/national_id/'.$filename))){
                    unlink(public_path('uploads/national_id/'.$filename));
                }
                DB::table('user_kyc')->where('user_id',decrypt($req->update_id))->delete(); 
            }
            if($res){
                return redirect('customer')->with('Mymessage', flashMessage('success','Record Updated Successfully'));
            }else{
                return redirect('customer')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function delete($eid) {
    
        $id = decrypt($eid);
        $res = User::find($id)->delete();
        if($res){
            return redirect('customer')->with('Mymessage', flashMessage('success','Record Deleted Successfully'));
        }else{
            return redirect('customer')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

}
