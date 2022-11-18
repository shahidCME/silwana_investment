<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $data['title'] = "Finance Person List";
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
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
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
                    return ($row->status == '1') ? '<button type="button" class="btn btn-success btn-sm">Active</button>' : '<button type="button" class="btn btn-danger btn-sm">Inactive</button>';
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
        $data['title'] = 'Add Finance Person';
        if($req->all()){
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
        $data['title'] = 'Eidt Finance Person';
        $data['action'] = url('financePersonEdit');
        if($req->all()){
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


}
