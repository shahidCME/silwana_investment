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
    public function __construct(){
        $this->middleware('adminAndSales');
    }

    public function index()
    {
        
        $data['page'] = "admin.users.list";
        $data['js'] = ['users'];
        $data['addCustomer'] = url('addCustomer');
        $data['title'] = "Investors";
        return view('admin/main_layout',$data);
    }
    
    public function getCustomerDataTable(Request $request){
        if ($request->ajax()) {
            $Session = Session::get('admin_login');
            $query = User::select('*');
            if($Session['role'] == '0'){
                $where = array(['admin_id','=',$Session['id']]);
                $query->where($where);
            }
            $query->orderBy('id','desc');   
            $data = $query->get();
            // dd($data);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->fname .' '. $row->lname;
                })
                ->addColumn('action', function($row){
                    $encryptedId = encrypt($row->id);
                    $editurl = "customerEdit/".$encryptedId;
                    $deleteurl = "customerDelete/".$encryptedId;
                    $kyc_doc = "kyc_doc/".$encryptedId;;
                   $btn = '<div class="dropdown" style="display:inline">
                   <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                       <i class="dw dw-more"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list manage-action" >
                       <a class="dropdown-item" href="'.url($editurl).'"><i class="dw dw-edit2"></i> Edit</a>
                       <a class="dropdown-item deleteRecord" href="'.url($deleteurl).'"><i class="dw dw-delete-3 "></i> Delete</a>
                       <a class="dropdown-item" href="'.url($kyc_doc).'"><i class="dw dw-file "></i> kyc doc</a>
                   </div>
               </div>'; 
                // if(isset($row->nationalIdImage)){
                //     $btn .='<a href='.url("uploads/national_id/".$row->nationalIdImage).' target="_blank" class="badge badge-success"><i class="fa fa-download"></i></a>';
                // }
                return $btn;
                })
                ->addColumn('mobile', function($row){
                    return (($row->country_code != NULL ) ? $row->country_code.'-' : '').$row->mobile;
                })
                ->addColumn('status', function($row){
                    $encryptedId = encrypt($row->id);
                    $statusUrl = "customerStatus/".$encryptedId;
                    return ($row->status == '1') ? '<a href="'.$statusUrl.'" type="button" class="badge badge-success">Active</a>' : '<a a href="'.$statusUrl.'" type="button" class="badge badge-danger   ">Inactive</a>';
                })
                ->rawColumns(['name','mobile','status','action'])
                ->make(true);
        }

        return view('admin/main_layout');
    }

    public function kyc_doc($user_id){
        $user_id = decrypt($user_id);
        $kyc = DB::table('user_kyc')->where('user_id',$user_id)->get();
        $data['page'] = "admin.users.kyc_doc_list";
        $data['record'] = $kyc;
        $data['title'] = "Kyc document list";
        return view('admin/main_layout',$data);
    }


    public function add(Request $req)
    {
        $data['page'] =  'admin.users.add';
        $data['action'] = url('addCustomer');
        $data['js'] = array('validateFile','users');
        $data['title'] = 'Add Investors';
        $data['countries'] = DB::table('countries')->get();
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => ['required','email', 
                    Rule::unique('users')->whereNull('deleted_at')
                ],
                'mobile' => 'required',
                'country_code'=>'required'
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required',
                'country_code.required'=>'Please select country code'
            ]);


            if(Session::has('admin_login')){
                $sessionValue = Session :: get('admin_login');
            }
            $res = new User();
            $res->admin_id = $sessionValue['id'];
            $res->country_id = $req->country;
            $res->fname = $req->fname; 
            $res->lname = $req->lname; 
            $res->nationality = $req->nationality; 
            $res->email = $req->email; 
            $res->password = bcrypt($req->password); 
            $res->gender = $req->gender; 
            $res->country_code = $req->country_code; 
            $res->mobile = $req->mobile; 
            $res->national_id = $req->national_id; 
            $res->date_of_expiry = dbDateFormat($req->date_of_expiry,true); 
            $res->status = $req->status; 
            $res->dob = dbDateFormat($req->dob,true); 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            $last_id = $res->id;
            if(isset($req->is_kyc)){
                for($key = 0 ; $key <= (count($req->name_document))-1; $key++) {        
                    if($req->hasfile('document_file')){
                        $file = $req->file('document_file')[$key];
                        $ext = $file->getClientOriginalExtension();
                        $filename = 'document_file_'.time().'.'.$ext;
                        $file->move(public_path('uploads/kyc_document'),$filename);
                    }  
                    // $image_path = $req->file('nationalIdImage')->store('kycPicture', 'public');
                        $valid_from = $req->valid_from[$key];
                        $valid_thru = $req->valid_thru[$key];
                        DB::table('user_kyc')->insert([
                            'user_id'=>$last_id,
                            'name_document'=> $req->name_document[$key],
                            'valid_from'=> ( !is_null ($valid_from) ) ? dbDateFormat($valid_from,true) : NULL,
                            'valid_thru'=> ( !is_null ($valid_thru) ) ? dbDateFormat($valid_thru,true) : NULL,
                            'document_file'=>$filename,
                            'created_at' => dbDateFormat(),
                            'updated_at' => dbDateFormat()
                        ]);
                }
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
                    $value->kycData = [];
                }
            }
            $data['editData'] = $res;
            $data['update_id'] = $eid;
        }
        // dd($data);
        $data['page'] = 'admin.users.edit';
        $data['title'] = 'Edit Investors ';
        $data['js'] = array('validateFile','users');
        $data['action'] = url('customerEdit');
        $data['countries'] = DB::table('countries')->get();
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => ['required','email',
                        Rule::unique('users')->whereNull('deleted_at')->ignore(decrypt($req->update_id)),
                    ],
                'mobile' => 'required',
                'country_code'=>'required'
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required',
                'country_code.required'=>'Please select country code'
            ]);
            $res = User::updateRecords($req->all()); 

            if(isset($req->is_kyc)){
                DB::table('user_kyc')->where('user_id',decrypt($req->update_id))->delete();

                for($key = 0 ; $key <= (count($req->name_document))-1; $key++) {
                            
                   $filename = (isset($req->edit_file[$key])) ? $req->edit_file[$key] : ''; 

                    if($req->hasfile('document_file') &&  $filename == ''){
                        $file = $req->file('document_file')[$key];
                        $ext = $file->getClientOriginalExtension();
                        $filename = 'document_file_'.time().'.'.$ext;
                        $file->move(public_path('uploads/kyc_document'),$filename);
                    }  
                        $valid_from = $req->valid_from[$key];
                        $valid_thru = $req->valid_thru[$key];
                        DB::table('user_kyc')->insert([
                            'user_id'=>decrypt($req->update_id),
                            'name_document'=> $req->name_document[$key],
                            'valid_from'=> ( !is_null ($valid_from) ) ? dbDateFormat($valid_from,true) : NULL,
                            'valid_thru'=> ( !is_null ($valid_thru) ) ? dbDateFormat($valid_thru,true) : NULL,
                            'document_file'=>$filename,
                            'created_at' => dbDateFormat(),
                            'updated_at' => dbDateFormat()
                        ]);
                }
            }else{
                $kycData = DB::table('user_kyc')->where('user_id', decrypt($req->update_id))->get();
                for($key = 0 ; $key <= (count($kycData))-1; $key++) {
                    if(file_exists(public_path('uploads/kyc_document/'.$kycData[$key]->document_file))){
                        unlink(public_path('uploads/kyc_document/'.$kycData[$key]->document_file));
                    }
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

    function gerNationality(Request $request){
        $id = $request->country_id;
        $data = DB::table('countries')->where('id',$id)->first();
        if(!empty($data)){
            $record = ['status'=>'1','nationality'=>$data->nationality];
        }else{
            $record = ['status'=>'0'];
        }
        echo json_encode($record);
    }

    public function customerStatus($eid){
        $id = decrypt($eid);
        $data= User :: where('id',$id)->get();   
        if($data[0]->status == '1'){
            $setStatus = '0';
        }else{
            $setStatus = '1';
        }
        $res = User::where('id',$id)->update(['status'=>$setStatus]);
        if($res){
            return redirect('customer')->with('Mymessage', flashMessage('success','Status Updated Successfully'));
        }else{
            return redirect('customer')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

}
