<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\File;
use App\Models\admin\Investment;
use App\Models\admin\Schema;
use App\Models\admin\User;
use DB;
use DataTables;

class InvestmentController extends Controller
{
    
    public function index()
    {
        $data['page'] = "admin.investment.list";
        $data['js'] = ['investment'];
        $data['addButton'] = url('addInvestment');
        $data['title'] = "Investment list";
        return view('admin/main_layout',$data);
    }
    
    public function getInvestmentDataTable(Request $request){
        if ($request->ajax()) {
            $Session = Session::get('admin_login');
            
            $query = DB:: table('investments as i');
              $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                    ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname');
            if($Session['super_admin'] == '0'){
                $id = $Session['id'];
                $query->where('i.admin_id',$id);
            }
            // Elq();
            $query->where('i.deleted_at',null);
            $data = $query->get();
            // Plq();
            // dd($data);
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('customer fullname',function($row){
                return $row->customerFname .' '.$row->customerLname;
            })
            ->addColumn('sales person',function($row){
                return $row->fname.' '.$row->lname;
            })
            ->addColumn('start date',function($row){
                return date('d F Y',strtotime($row->start_date));
            })
            ->addColumn('return type',function($row){
                return ($row->return_type =='0') ? "Monthly" : "Yearly";
            })
             ->addColumn('action', function($row){      
                    $role = (admin_login()['role'] == "3") ? 'd-none' : '';              
                    $encryptedId = encrypt($row->id);
                    $editurl = "InvestmentEdit/".$encryptedId;
                    $deleteurl = "InvestmentDelete/".$encryptedId;
                    $view = "InvestmentDocument/".$encryptedId;
                    $roi = "roi/".$encryptedId;
                   $btn = '<div class="dropdown">
                   <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                       <i class="dw dw-more"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                       <a class="dropdown-item" href="'.url($roi).'"><i class="dw dw-file"></i> ROI</a>
                       <a class="dropdown-item" href="'.url($view).'"><i class="dw dw-eye"></i> View</a>
                       <a class="dropdown-item '.$role.'" href="'.url($editurl).'" ><i class="dw dw-edit2"></i> Edit</a>
                       <a class="dropdown-item deleteRecord '.$role.'" href="'.url($deleteurl).'"><i class="dw dw-delete-3 "></i> Delete</a>
                     
                     
                   </div>
               </div>'; 
                         return $btn;
                })
                ->addColumn('status', function($row){
                    if($row->status == '0') {
                            
                        $sttus ='<button type="button" class="badge badge-danger">Rejected</button>' ;
                    }elseif($row->status == '1'){
                        $sttus ='<button type="button" class="badge badge-success">Approved</button>';
                    }else{

                    //     $sttus ='<select class="form_group">
                    //     <option><button type="button" class="badge badge-danger">Rejected</button></option>
                    // </select>';
                        $sttus ='<button type="button" class="badge badge-warning">Pending</button>';
                    } 
                    return $sttus;
                })
                ->rawColumns(['return type','start date','sales person','customer name','status','action'])
                ->make(true);
        }

        return view('admin/main_layout');
    }


    public function add(Request $req)
    {   
        $sess = Session::get('admin_login');
        if($sess['role'] =='3'){
            return Redirect:: to('Investment')->with('Mymessage', flashMessage('danger','You are not autherised to access this route'));
        }
        $data['page'] =  'admin.investment.add';
        $data['action'] = url('addInvestment');
        $data['js'] = array('validateFile','investment');
        $data['title'] = 'Add Investment';
        $session = admin_login();
        $customer = User::where('status','=','1')->get();
        $data['getCustomer'] = $customer;
        $schema = schema::where('status','=','1')->get();
        $data['getSchema'] = $schema;
        
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'customer' => 'required',
                'schema' => 'required',
                'amount' => 'required',
                'return_percentage' => 'required',
                'start_date' => 'required',
                
            ], [
                'customer.required' => 'Please select customer',
                'schema.required' => 'Please select schema',
                'amount.required' => 'Please enter amount',
                'return_percentage.required' => 'Please enter return percentage',
                'start_date.required' => 'Please select start date',
            ]);
      
            // $image1 ='';
            // $investDoc ='';
            // $otherDoc ='';
            if($req->hasfile('contract_reciept')){
                if (!file_exists(public_path('uploads/contract_reciept'))) {
                    mkdir(public_path('uploads/contract_reciept'), 0777, true);
                }
                $file = $req->file('contract_reciept');
                $ext = $file->getClientOriginalExtension();
                $image1 = 'contract_reciept_'.time().'.'.$ext;
                $file->move(public_path('uploads/contract_reciept'),$image1);
            }
            if($req->hasfile('invest_document')){
                // echo '3';die;
                if (!file_exists(public_path('uploads/invest_document'))) {
                    mkdir(public_path('uploads/invest_document'), 0777, true);
                }
                $file = $req->file('invest_document');
                $ext = $file->getClientOriginalExtension();
                $investDoc = 'invest_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/invest_document'),$investDoc);
              
            }
            if($req->hasfile('other_document')){
                if (!file_exists(public_path('uploads/other_document'))) {
                    mkdir(public_path('uploads/other_document'), 0777, true);
                }
                $file = $req->file('other_document');
                $ext = $file->getClientOriginalExtension();
                $otherDoc = 'other_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/other_document'),$otherDoc);
            }
            $res = new Investment();
            $res->status = $req->status; 
            if($session['role'] == '0'){
                $res->status = '2';
            }
            $res->admin_id = admin_login()['id']; 
            $res->user_id = $req->customer; 
            $res->schema_id = $req->schema;
            $res->tenure = $req->tenure;
            $res->amount = $req->amount; 
            $res->return_type = $req->return_type; 
            $res->start_date = dbDateFormat($req->start_date,true); 
            $res->return_percentage = $req->return_percentage; 
            $res->contract_reciept = ($image1 != '') ? $image1 : ''; 
            $res->investment_doc = ($investDoc!='') ? $investDoc : ''; 
            $res->other_doc = (!isset($otherDoc)) ? '' : $otherDoc; 
            $res->created_at = dbDateFormat(); 
            $res->updated_at = dbDateFormat(); 
            $res->save();
            $last_id = $res->id;
            for ($i=1; $i <= $res->tenure  ; $i++) { 
                if($res->return_type == '0'){
                    $time = strtotime($res->start_date);
                    $final_date = date("Y-m-d", strtotime("+".$i." month", $time));
                }else{
                    $final_date = date('Y-m-d', strtotime("+".$i." years"));
                }
                $returnAmount = ($res->amount * $req->return_percentage)/100;
                $willInsert = [
                    'investment_id'=>$last_id,
                    'return_amount'=>$returnAmount,
                    'date_of_return'=> $final_date,
                    'status'=>'0',
                    'return_percentage'=>$req->return_percentage,
                    'investment_amount'=>$req->amount,
                    'created_at' => dbDateFormat(),
                    'updated_at' => dbDateFormat() 
                ];
                DB::table('roi')->insert($willInsert);
            }

            if($res){
                return redirect('Investment')->with('Mymessage', flashMessage('success','Record Inserted Successfully'));
            }else{
                return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function edit ($eid='',Request $req){
        $customer = User::where('status','=','1')->get();
        $data['getCustomer'] = $customer;
        $schema = schema::where('status','=','1')->get();
        $data['getSchema'] = $schema;
        if($eid!= ''){
            $id =  decrypt($eid);
            $res =  Investment::getData($id);
            $data['editData'] = $res;
            $data['update_id'] = $eid;
        }
        $data['page'] = 'admin.investment.edit';
        $data['title'] = 'Eidt Investment ';
        $data['js'] = array('validateFile','investment');
        $data['action'] = url('InvestmentEdit');
        if($req->all()){
            // dd($req->all());
            $validatedData = $req->validate([
                'customer' => 'required',
                'schema' => 'required',
                'amount' => 'required',
                'return_percentage' => 'required',
                'start_date' => 'required',
                
            ], [
                'customer.required' => 'Please select customer',
                'schema.required' => 'Please select schema',
                'amount.required' => 'Please enter amount',
                'return_percentage.required' => 'Please enter return percentage',
                'start_date.required' => 'Please select start date',
            ]);


            $filename = (isset($req->old_contract_reciept) && $req->old_contract_reciept != NULL) ? $req->old_contract_reciept : '';

            if($req->hasfile('edit_contract_reciept')){
                if($filename != NULL && file_exists(public_path('uploads/contract_reciept/'.$filename)) ){
                    unlink(public_path('uploads/contract_reciept/'.$filename));
                }
                $file = $req->file('edit_contract_reciept');
                $ext = $file->getClientOriginalExtension();
                $filename = 'contract_reciept_'.time().'.'.$ext;
                $file->move(public_path('uploads/contract_reciept'),$filename);
            }
            
            
            $invest_document = (isset($req->old_invest_document) && $req->old_invest_document != NULL) ? $req->old_invest_document : '';

            if($req->hasfile('edit_invest_document')){
                if($invest_document != NULL && file_exists(public_path('uploads/invest_document/'.$invest_document)) ){
                    unlink(public_path('uploads/invest_document/'.$invest_document));
                }
                $file = $req->file('edit_invest_document');
                $ext = $file->getClientOriginalExtension();
                $invest_document = 'invest_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/invest_document'),$invest_document);
            }


            $other_document = (isset($req->old_other_document) && $req->old_other_document != NULL) ? $req->old_other_document : '';

            if($req->hasfile('edit_other_document')){
                if($other_document != NULL && file_exists(public_path('uploads/other_document/'.$other_document)) ){
                    unlink(public_path('uploads/other_document/'.$other_document));
                }
                $file = $req->file('edit_other_document');
                $ext = $file->getClientOriginalExtension();
                $other_document = 'other_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/other_document'),$other_document);
            }

            $res = Investment :: updateRecords($req->all(),$filename,$invest_document,$other_document);

              // $filename = $req->old_image;
            if($res){
                // $this->notification($req->all());
                return redirect('Investment')->with('Mymessage', flashMessage('success','Record Updated Successfully'));
            }else{
                return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);
    }

    function notification($postData){
        $status = 'Pendding';
        if(isset($postData['status'])){
            if($postData['status'] == '0'){
                $status = 'Rejected';
            }elseif($postData['status'] == '1'){
                $status = 'Approved';
            }else{
                $status = 'Pendding';
            }
        }

        $schema = schema::where('id',$postData['schema'])->get();
        $insertData = ['admin_id'=>$postData['admin_id'],'user_id'=>$postData['customer'],'title'=>'Investment','description'=>'Application of investment in '.$schema[0]->name.' is '.$status.'','created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
        $this->insertNotification($insertData);
        return true;
    }

    function delete($eid) {
        $id = decrypt($eid);
        $res = Investment::find($id)->delete();
        if($res){
            return redirect('Investment')->with('Mymessage', flashMessage('success','Record Deleted Successfully'));
        }else{
            return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

    public function investmentDocument($eid){
        $id = decrypt($eid);
        $query = DB:: table('investments as i');
                $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                    ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname','s.details');
                    $query->where('i.id',$id);
                    $query->where('i.deleted_at',null);
                    $viewData = $query->get();
                    // dd($viewData);
        $data['viewData'] = $viewData;
        $data['page'] = 'admin.investment.investmentDocument';
        return view('admin/main_layout',$data);

    }

    public function getRoi($eid='',Request $req){
        if($eid != ''){
            $id = decrypt($eid);
            // $invest = DB::table('investments')->where('id',$id)->get();
            // if($invest[0]->return_type == '0'){
            //     $time = strtotime($invest[0]->start_date);
            //     $final = date("Y-m-d", strtotime("+1 month", $time));
            // }else{
            //     $time = strtotime($invest[0]->start_date);
            //     $final = date("Y-m-d", strtotime("+1 year", $time));
            // }
            // Elq();
            $record = DB::table('roi')->where('investment_id',$id)->get();
            // Plq();
            $data['investment_id'] = $eid;
            $data['roi'] = $record;
            $data['title'] = 'Return of Investment';
            $data['page']  = 'admin.investment.roi';
            $data['js'] = ['investment','validateFile'];
        }
        if($req->all()){
            if (!file_exists(public_path('uploads/payment_trasfer_reciept'))) {
                mkdir(public_path('uploads/payment_trasfer_reciept'), 0777, true);
            }
            if($req->hasfile('payment_trasfer_reciept')){
                $file = $req->file('payment_trasfer_reciept');
                $ext = $file->getClientOriginalExtension();
                $fileName = 'payment_trasfer_reciept_'.time().'.'.$ext;
                $file->move(public_path('uploads/payment_trasfer_reciept'),$fileName);
            }
            $uid = decrypt($req->roi_id);
            $result = DB::table('roi')->where('id',$uid)->update(['payment_trasfer_reciept'=>$fileName,'status'=>'1','updated_at'=>dbDateFormat()]);
            if($result){
                return redirect('roi/'.$req->investment_id)->with('Mymessage', flashMessage('success','File Submitted Successfully'));
            }else{
                return redirect('roi/'.$req->investment_id)->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);

    }
}
