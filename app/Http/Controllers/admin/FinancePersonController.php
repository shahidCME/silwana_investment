<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; 
use App\Models\admin\Admin;
use DB;
use DataTables;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancePersonController extends Controller
{
    // public function __construct(){
    //     $this->middleware('RestrictUrl');
    // }

     function index(){
        $data['page'] = 'admin.financePerson.list';
        $data['js'] = array('financePerson');
        $data['title'] = "Finance Team";
        $data['addBtn'] = url('addFinancePerson');
        return view('admin/main_layout',$data);
    }

    function getFinancePersonDataTable(Request $request){
        if ($request->ajax()) {
            $Session = Session::get('admin_login');
            $where = array(['id','!=',$Session['id']], ['role','=','3']);
            // dd($where);
            // dd($request->all());
            $data = Admin::where($where)->orderBy('id','desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $row->name = $row->fname.' '.$row->lname;
                    $encryptedId = encrypt($row->id);
                    $editurl = "financePersonEdit/".$encryptedId;
                    $deleteurl = "financePersonDelete/".$encryptedId;
                   $btn = '<div class="dropdown">
                   <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                       <i class="dw dw-more"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list manage-action">
                       <a class="dropdown-item" href="'.url($editurl).'"><i class="dw dw-edit2"></i> Edit</a>
                       <a class="dropdown-item deleteRecord" href="'.url($deleteurl).'"><i class="dw dw-delete-3 "></i> Delete</a>
                     
                     
                   </div>
               </div>'; 
                         return $btn;
                })
                ->addColumn('name', function($row){
                    return $row->fname.' '.$row->lname;
                })
                ->addColumn('status', function($row){
                    $encryptedId = encrypt($row->id);
                    $statusUrl = "financeStatus/".$encryptedId;
                    return ($row->status == '1') ? '<a href="'.url($statusUrl).'" type="button" class="btn btn-success btn-sm">Active</a>' : '<a href="'.url($statusUrl).'" type="button" class="btn btn-danger btn-sm">Inactive</a>';
                })
                ->rawColumns(['status','name','action'])
                ->make(true);
        }

        return view('admin/main_layout');

    }
    
    function add(Request $req){
        
        $data['page'] =  'admin.financePerson.add';
        $data['action'] = url('addFinancePerson');
        $data['js'] = array('validateFile');
        $data['title'] = 'Add Finance';
        if($req->all()){
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => ['required','email', 
                    Rule::unique('admins')->whereNull('deleted_at')
                ],
                'mobile' => 'required',
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required'
            ]);

            $res = Admin::addFinancePerson($req->all());
            if($res){
                return redirect('financePerson')->with('Mymessage', flashMessage('success','Record Inserted Successfully'));
            }else{
                return redirect('financePerson')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function edit ($eid='',Request $req){
        
        if($eid!= ''){
            $id =  decrypt($eid);
            $res =  Admin :: getData($id);
            $data['editData'] = $res;
            $data['update_id'] = $eid;
        }
        $data['page'] = 'admin.financePerson.edit';
        $data['title'] = 'Eidt Finance ';
        $data['action'] = url('financePersonEdit');
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'fname' => 'required',
                'lname' => 'required',
                'email' => ['required','email', 
                    Rule::unique('admins')->whereNull('deleted_at')->ignore(decrypt($req->update_id))
                ],
                'mobile' => 'required',
            ], [
                'fname.required' => 'Please enter first name',
                'lname.required' => 'Please enter last name',
                'mobile.required'=>'Mobile is required'
            ]);
            $res = Admin :: updateFinanceRecords($req->all());
            if($res){
                return redirect('financePerson')->with('Mymessage', flashMessage('success','Record Updated Successfully'));
            }else{
                return redirect('financePerson')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function delete($eid) {
        
        $id = decrypt($eid);
        $res= Admin :: deleteRecord($id);
        $error = '<div class="alert alert-success">Record Deleted Successfully</div>';
        if($res){
            return redirect('financePerson')->with('Mymessage', flashMessage('success','Record Deleted Successfully'));
        }else{
            return redirect('financePerson')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

    public function financeStatus($eid){
        $id = decrypt($eid);
        $data= Admin :: where('id',$id)->get();
        
        if($data[0]->status == '1'){
            $setStatus = '0';
        }else{
            $setStatus = '1';
        }
        $res = Admin::where('id',$id)->update(['status'=>$setStatus]);
        if($res){
            return redirect('financePerson')->with('Mymessage', flashMessage('success','Status Updated Successfully'));
        }else{
            return redirect('financePerson')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }


}
