<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\File;
use DB;
use DataTables;

class SchemaController extends Controller
{
    public function __construct(){
        $this->middleware('RestrictUrl');
    }

    public function index()
    {
        $data['page'] = "admin.schema.list";
        $data['js'] = ['schema'];
        $data['addButton'] = url('addSchema');
        $data['title'] = "Schema list";
        return view('admin/main_layout',$data);
    }
    
    public function getSchemaDataTable(Request $request){
        if ($request->ajax()) {
            $Session = Session::get('admin_login');
            $data = Schema:: orderBy('id','desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $row->name = $row->fname.' '.$row->lname;
                    $encryptedId = encrypt($row->id);
                    $editurl = "SchemaEdit/".$encryptedId;
                    $deleteurl = "SchemaDelete/".$encryptedId;
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
                ->addColumn('status', function($row){
                    return ($row->status == '1') ? '<button type="button" class="badge badge-success">Active</button>' : '<button type="button" class="badge badge-danger   ">Inactive</button>';
                })
                ->addColumn('image', function($row){
                    $image = url('uploads/schema/'.$row->image);
                    return ($row->image != '') ? "<div style='width: 100px;hieght:100px'> <img  src=".$image."  ></div>" : '<button type="button" class="badge badge-danger   ">Inactive</button>';
                })
                ->rawColumns(['status','image','action'])
                ->make(true);
        }

        return view('admin/main_layout');
    }

    public function add(Request $req)
    {
        $data['page'] =  'admin.schema.add';
        $data['action'] = url('addSchema');
        $data['js'] = array('validateFile','schema');
        $data['title'] = 'Add Schema';
        $data['cancelBtn'] = url('Schema');

        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'name' => 'required',
            ], [
                'name.required' => 'Name is required',
            ]);
            if($req->hasfile('image')){
                $file = $req->file('image');
                $ext = $file->getClientOriginalExtension();
                $image = 'image_'.time().'.'.$ext;
                $file->move(public_path('uploads/schema'),$image);
                // $image_path = $req->file('nationalIdImage')->store('kycPicture', 'public');
            }
            if($req->hasfile('schema_document')){
                $file = $req->file('schema_document');
                $ext = $file->getClientOriginalExtension();
                $filename = 'document_'.time().'.'.$ext;
                $file->move(public_path('uploads/schema_doc'),$filename);
              
            }
            $res = new Schema();
            $res->name = $req->name; 
            $res->type = $req->type;
            $res->details = $req->details; 
            $res->image = $image; 
            $res->documents = $filename; 
            $res->start_date = dbDateFormat($req->start_date,true); 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            if($res){
                return redirect('Schema')->with('Mymessage', flashMessage('success','Record Inserted Successfully'));
            }else{
                return redirect('Schema')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function edit ($eid='',Request $req){
        if($eid!= ''){
            $id =  decrypt($eid);
            $res =  Schema::getData($id);
            $data['editData'] = $res;
            $data['update_id'] = $eid;
        }
        $data['page'] = 'admin.schema.edit';
        $data['title'] = 'Eidt Schema ';
        $data['js'] = array('validateFile','schema');
        $data['action'] = url('SchemaEdit');
        $data['cancelBtn'] = url('Schema');
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'name' => 'required',
            ], [
                'name.required' => 'Name is required',
            ]);


            $filename = (isset($req->old_image) && $req->old_image != NULL) ? $req->old_image : '';
            if($req->hasfile('editimage')){
                if($filename != null && file_exists(public_path('uploads/schema/'.$filename)) ){
                    unlink(public_path('uploads/schema/'.$filename));
                }
                $file = $req->file('editimage');
                $ext = $file->getClientOriginalExtension();
                $filename = 'image_'.time().'.'.$ext;
                $file->move(public_path('uploads/schema'),$filename);
            }
            
            
            $document = (isset($req->old_document) && $req->old_document != NULL) ? $req->old_document : '';
            if($req->hasfile('edit_schema_document')){
                if($filename != null && file_exists(public_path('uploads/schema/'.$filename)) ){
                    unlink(public_path('uploads/schema_doc/'.$document));
                }
                $file = $req->file('edit_schema_document');
                $ext = $file->getClientOriginalExtension();
                $document = 'document_'.time().'.'.$ext;
                $file->move(public_path('uploads/schema_doc'),$document);
            }
            $res = Schema :: updateRecords($req->all(),$filename,$document);
              // $filename = $req->old_image;
            if($res){
                return redirect('Schema')->with('Mymessage', flashMessage('success','Record Updated Successfully'));
            }else{
                return redirect('Schema')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function delete($eid) {
        $id = decrypt($eid);
        // $re = Schema::find($id)->get();
        // $image = $re[0]->image; 
        // unlink(public_path('uploads/schema/'.$image)); 
        // $document = $re[0]->documents; 
        // unlink(public_path('uploads/schema_doc/'.$document));
        $res = Schema::find($id)->delete();
        if($res){
            return redirect('Schema')->with('Mymessage', flashMessage('success','Record Deleted Successfully'));
        }else{
            return redirect('Schema')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

}
