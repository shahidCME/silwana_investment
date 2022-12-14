<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\File;
use App\Models\admin\Investment;
use App\Models\admin\Schema;
use App\Models\admin\User;
use App\Models\Device;
use DB;
use DataTables;
use PDF;
use Stichoza\GoogleTranslate\GoogleTranslate;

class InvestmentController extends Controller
{
    
    public function index()
    {
        $data['page'] = "admin.investment.list";
        $data['js'] = ['investment','validateFile'];
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
            $query->where('i.deleted_at',null);
            $query->where('u.deleted_at',null);
            $query->where('i.status','!=','9');
            $query->orderBy('id','desc');
            $data = $query->get();
            foreach ($data as $key => $value) {            
                if($value->return_type == '0'){
                    $start_date = strtotime($value->start_date);
                    $year = (12/$value->tenure);
                    $end_date = date('Y-m-d', strtotime("+".$year.' year',$start_date));
                    $value->contract_end_date = $end_date;
                }else{
                    $start_date = date('Y-m-d',strtotime($value->start_date));
                    $contract_end_date = date('Y-m-d', strtotime("+".$value->tenure." year",strtotime($start_date)));
                    $value->contract_end_date = $contract_end_date;
                }
            }
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
            ->addColumn('end date',function($row){
                return date('d F Y',strtotime($row->contract_end_date));
            })
            ->addColumn('return type',function($row){
                return ($row->return_type =='0') ? "Monthly" : "Yearly";
            })
             ->addColumn('action', function($row){      
                    $role = (admin_login()['role'] == "3") ? 'd-none' : '';              
                    $cancel = (admin_login()['role'] == "3" || admin_login()['role'] == "1") ? '' : 'd-none';
                    $notView = (admin_login()['role'] == "0") ? 'd-none' : '';
                    $encryptedId = encrypt($row->id);
                    $editurl = "InvestmentEdit/".$encryptedId;
                    $deleteurl = "InvestmentDelete/".$encryptedId;
                    $view = "InvestmentDocument/".$encryptedId;
                    $conract = "contract/".$encryptedId;
                    $roi = "roi/".$encryptedId;
                   $btn = '<div class="dropdown" style="display:inline">
                   <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                       <i class="dw dw-more"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list manage-action">
                       <a class="dropdown-item '.$notView.'" href="'.url($roi).'"><i class="dw dw-file"></i> ROI</a>
                       <a class="dropdown-item '.$notView.'" href="'.url($view).'"><i class="dw dw-eye"></i> View</a>
                       <a class="dropdown-item '.$notView.'" href="'.url($conract).'"><i class="dw dw-file"></i> Contract</a>
                       <a class="dropdown-item '.$role.'" href="'.url($editurl).'" ><i class="dw dw-edit2"></i> Edit</a>
                       <a class="dropdown-item deleteRecord '.$role.'" href="'.url($deleteurl).'"><i class="dw dw-delete-3 "></i> Delete</a>
                       <a class="dropdown-item '.$cancel.' CancelContract" data-id='.$encryptedId.' data-toggle="modal" data-target="#Medium-modal"><i class="dw dw-cancel "></i> Cancel</a>
                   </div>
               </div>';
               if(admin_login()['role'] != '0'){
                   if($row->contract_pdf != ''){
                       $d_url = url('uploads/contract_pdf/'.$row->contract_pdf);
                       $btn .='<a href="'.$d_url.'" target="_blank" class="badge badge-success" ><i class="fa fa-download"></i></a>';
                    }
                } 
                    return $btn;
                })
                ->addColumn('status', function($row){
                    if($row->status == '0') {
                            
                        $sttus ='<button type="button" class="badge badge-danger">Rejected</button>' ;
                    }elseif($row->status == '1'){
                        $sttus ='<button type="button" class="badge badge-success">Approved</button>';
                    }else{
                        $sttus ='<button type="button" class="badge badge-warning">Pending</button>';
                    } 
                   
                    return $sttus;
                })
                ->rawColumns(['return type','start date','end date','sales person','customer name','status','action'])
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
      
            $image1 ='';
            if($req->hasfile('contract_reciept')){
                if (!file_exists(public_path('uploads/contract_reciept'))) {
                    mkdir(public_path('uploads/contract_reciept'), 0777, true);
                }
                $file = $req->file('contract_reciept');
                $ext = $file->getClientOriginalExtension();
                $image1 = 'contract_reciept_'.time().'.'.$ext;
                $file->move(public_path('uploads/contract_reciept'),$image1);
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

    function edit($eid='',Request $req){
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

            $investData = Investment::where('id', decrypt($req->update_id))->get();
            $res = Investment::updateRecords($req->all(),$filename,$invest_document,$other_document);
            if(	$req->status=='1'){
                // dd($req->all());
                if (!file_exists(public_path('uploads/contract_pdf'))) {
                    mkdir(public_path('uploads/contract_pdf'), 0777, true);
                }
                // if($req->edit_contract_pdf != '' && file_exists(public_path('uploads/contract_pdf'.$req->edit_contract_pdf))){
                //     unlink(public_path('uploads/contract_pdf/'.$req->edit_contract_pdf));
                // }
                $id = decrypt($req->update_id);
                $query=DB:: table('investments as i');
                $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('countries as c', 'c.id', '=', 'u.country_id')
                    // ->leftJoin('user_kyc as k', 'u.id', '=', 'k.user_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname','u.mobile','u.dob','u.date_of_expiry','u.national_id','c.nationality');
                    $query->where('i.id',$id);
                    $query->where('i.deleted_at',null);
                    $viewData = $query->groupBy('i.id')->get();
                    $data['viewData'] = $viewData;
                    $data['arabic'] = [];
                    $start_date = strtotime($req->start_date);
                    if($req->return_type == '0'){
                        // dd($req->start_date);
                        $start_date = strtotime($req->start_date);
                        $year = $req->tenure;
                        $end_date = date('Y-m-d', strtotime("+".$year.' month',$start_date));
                        // ddd($end_date);
                        $data['viewData'][0]->contract_start_date = dbDateFormat($req->start_date,true);
                        $data['viewData'][0]->contract_end_date = $end_date;
                    }else{
                        $start_date = date('Y-m-d',strtotime($req->start_date));
                        $contract_end_date = date('Y-m-d', strtotime("+".$req->tenure." year",strtotime($start_date)));
                        $data['viewData'][0]->contract_start_date = dbDateFormat($req->start_date,true);
                        $data['viewData'][0]->contract_end_date = $contract_end_date;
                    }
                      $contractStartDateFraction = explode("-",date("d-F-Y",strtotime($start_date)));
                      $data['viewData'][0]->day   =  $day = $contractStartDateFraction[0];
                      $data['viewData'][0]->month =  $month = $contractStartDateFraction[1];
                      $data['viewData'][0]->year  =  $year = $contractStartDateFraction[2];
                      $viewData[0]->amountArabic = convertNumberToWord($viewData[0]->amount);
                    //   ddd($data['viewData'][0]->contract_end_date);
                      if($req->lang == '1'){
                        
                        $tr = new GoogleTranslate();
                        $data['arabic']['customerFname'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->customerFname);
                        $data['arabic']['customerLname'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->customerLname);
                        $data['arabic']['nationality'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->nationality);
                        // $data['arabic']['national_id'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->national_id);
                        $data['arabic']['national_id'] = $viewData[0]->national_id;
                        $data['arabic']['nationality'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->nationality);
                        $date_of_expiry = date('d-F-Y',strtotime($viewData[0]->date_of_expiry));
                        $data['arabic']['date_of_expiry'] = $tr->setSource('en')->setTarget('ar')->translate($date_of_expiry);
                        $dob = date('d-F-Y',strtotime($viewData[0]->dob));
                        $data['arabic']['dob'] = $tr->setSource('en')->setTarget('ar')->translate($dob);
                        $data['arabic']['amount'] = $tr->setSource('en')->setTarget('ar')->translate(($viewData[0]->amount));
                        $data['arabic']['amountArabic'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->amountArabic);
                        $data['arabic']['contract_end_date'] = $viewData[0]->contract_end_date;
                        $data['arabic']['contract_start_date'] = $viewData[0]->contract_start_date;

                        $data['arabic']['day'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->day);
                        $data['arabic']['month'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->month);
                        $data['arabic']['year'] = $tr->setSource('en')->setTarget('ar')->translate(strtolower(convertNumberToWord($viewData[0]->year)));
                    }
                    $pdf = PDF::loadView('admin.contractTemplate.'.strtolower($req->contract), $data);
                    $filename = strtolower($req->contract).'_'.time().'.pdf';
                    $pdf->save(public_path('uploads/contract_pdf/').$filename);

                    
                    $availableAmount = $investData[0]->amount;
                    $availableStartData = $investData[0]->start_date;
                    // dd($req->all());
                    if(($investData[0]->contract_pdf == NULL) || ( ($availableAmount != $req->amount) || ($availableStartData != dbDateFormat($req->start_date,true)) )){
                        $willUpdateFiles = DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date])->get();
                       if(!empty($willUpdateFiles->all())){
                            DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date])->update(
                                [
                                    'terminate_date'=>dbDateFormat($req->start_date,true),
                                    'updated_at' => dbDateFormat() 
                                ]  
                                );
                        }
                        DB::table('contract_files')->insert(
                            [
                                'investment_id'=> $id,
                                'user_id' =>$viewData[0]->user_id,
                                'start_date' => dbDateFormat($req->start_date,true),
                                'end_date'   => $data['viewData'][0]->contract_end_date,
                                'contract_pdf'=>$filename,
                                'created_at' => dbDateFormat(),
                                'updated_at' => dbDateFormat() 
                                ]  
                            );
                    }else{
                        $willUpdateFiles = DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date])->get();
                        // dd($willUpdateFiles->all());
                        if(!empty($willUpdateFiles->all())){

                            if(file_exists(public_path('uploads/contract_pdf/'.$willUpdateFiles[0]->contract_pdf))){
                                unlink(public_path('uploads/contract_pdf/'.$willUpdateFiles[0]->contract_pdf));
                            }
                            
                            DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date,'created_at'=>$investData[0]->created_at])->update(
                                [
                                    'contract_pdf'=>$filename,
                                    'updated_at' => dbDateFormat() 
                                ]  
                                );
                        }else{
                            DB::table('contract_files')->insert(
                                [
                                    'investment_id'=> $id,
                                    'user_id' =>$viewData[0]->user_id,
                                    'start_date' => dbDateFormat($req->start_date,true),
                                    'end_date'   => $data['viewData'][0]->contract_end_date,
                                    'contract_pdf'=>$filename,
                                    'created_at' => dbDateFormat(),
                                    'updated_at' => dbDateFormat() 
                                    ]  
                                );
                        }
                    }
                    DB::table('investments')->where('id',$id)->update(['contract_pdf'=>$filename,'language'=>$req->lang,'contract_type'=>$req->contract]); 
                    // for user
                    $plan = Schema::where('id',$viewData[0]->schema_id)->get();
                    $planName = $plan[0]->name;
                    $device = Device::where(['user_id'=>$viewData[0]->user_id,'role'=>'2'])->get();
                    if(!empty($device->all())){
                        $notification_id = $device[0]->token;
                        $title = $planName;
                        $message =  'Application for '.$planName. ' is approved';
                        $id = $viewData[0]->user_id;
                        $type = $device[0]->type;
                        send_notification_FCM($notification_id, $title, $message, $id,$type);
                    }
                    $message =  'Application for '.$planName. ' is approved';
                    $insertData = ['for_role'=>'2','user_id'=>$viewData[0]->user_id,'title'=>$planName,'description'=>$message,'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                    $this->insertNotification($insertData);
                }
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
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname','s.details','s.documents');
                    $query->where('i.id',$id);
                    $query->where('i.deleted_at',null);
                    $viewData = $query->get();
                        foreach ($viewData as $key => $value) {
                            
                            if($value->return_type == '0'){
                                // dd($req->start_date);
                            $start_date = strtotime($value->start_date);
                            $year = (12/$value->tenure);
                            $end_date = date('Y-m-d', strtotime("+".$year.' year',$start_date));
                            $value->contract_end_date = $end_date;
                        }else{
                            $start_date = date('Y-m-d',strtotime($value->start_date));
                            $contract_end_date = date('Y-m-d', strtotime("+".$value->tenure." year",strtotime($start_date)));
                            $value->contract_end_date = $contract_end_date;
                        }
                    }

                    // dd($viewData);
        $data['viewData'] = $viewData;
        $data['page'] = 'admin.investment.investmentDocument';
        return view('admin/main_layout',$data);

    }

    public function getRoi($eid='',Request $req){
        if($eid != ''){
            $id = decrypt($eid);
            
            $query = DB:: table('investments as i');
                $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->select('u.*');
                $query->where('i.id',$id);
                $userData = $query->get();
                $data['customer_name'] = $userData[0]->fname. ' '.$userData[0]->lname; 

            $record = DB::table('roi')->where('investment_id',$id)->get();
            // Plq();
            $data['investment_id'] = $eid;
            $data['roi'] = $record;
            $data['title'] = 'Return of Investment';
            $data['page']  = 'admin.investment.roi';
            $data['js'] = ['investment','validateFile'];
        }
        if($req->all()){
        //    dd($req->all());
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
                $investment_id = decrypt($req->investment_id);
                $investment = DB::table('investments')->where('id',$investment_id)->get();
                // dd($investment);
                    $returnType = ($investment[0]->return_type == '0') ? 'Monthly' : 'Yearly';
                    $schema = schema::where('id',$investment[0]->schema_id)->get();

                    $user = USER::where('id',$investment[0]->user_id)->get();
                    $fname = $user[0]->fname;  
                    $device = Device::where(['user_id'=>$investment[0]->admin_id,'role'=>'1'])->get();
                    if(!empty($device->all())){
                        $notification_id = $device[0]->token;
                        $title = $returnType.' Return of '.$fname;
                        $message = $returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y');
                        $id = $investment[0]->admin_id;
                        $type = $device[0]->type;
                        send_notification_FCM($notification_id, $title, $message, $id,$type);
                    }
                    $insertData = ['for_role'=>'1','user_id'=>admin_login()['id'],'title'=>$returnType.' Return of '.$fname,'description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y'),'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                    $this->insertNotification($insertData);
                    
                    // for user
                    $device = Device::where(['user_id'=>$investment[0]->user_id,'role'=>'2'])->get();
                    if(!empty($device->all())){
                        $notification_id = $device[0]->token;
                        $title = $returnType.' Return of '.$fname;
                        $message = $returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y');;
                        $id = $investment[0]->admin_id;
                        $type = $device[0]->type;
                        send_notification_FCM($notification_id, $title, $message, $id,$type);
                    }
                    $insertData = ['for_role'=>'1','user_id'=>admin_login()['id'],'title'=>$returnType.' Return','description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y'),'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                    $this->insertNotification($insertData);
                    // $insertData = ['for_role'=>'2','user_id'=>$investment[0]->user_id,'title'=>$returnType.' Return','description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y'),'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                    // $this->insertNotification($insertData);
                
                return redirect('roi/'.$req->investment_id)->with('Mymessage', flashMessage('success','File Submitted Successfully'));
            }else{
                return redirect('roi/'.$req->investment_id)->with('Mymessage', flashMessage('danger','Something Went Wrong'));
            }
        }
        return view('admin/main_layout',$data);

    }
    
    public function contractCancel(Request $request){
        $uid = decrypt($request->investment_id);
        $res = Investment::where('id',$uid)->update(['status'=>'9','contractCancelComment'=>$request->contractCancelComment,'updated_at'=>dbDateFormat()]);
        if($res){
            return redirect('Investment')->with('Mymessage', flashMessage('success','Investment Canceled Successfully'));
        }else{
            return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
        }
    }

    public function cancelledInvestment(){
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
            $query->where('i.deleted_at',null);
            $query->where('i.status','9');
            $query->orderBy('id','desc');
            $record = $query->get();
        // dd($record);
        $data['page']  = 'admin.investment.cancelledInvestment';
        $data['js'] = ['cancelInvestment'];
        $data['cancelledInvestment'] = $record;
        $data['title'] = 'cancelled Investment';
        
        return view('admin/main_layout',$data);

    }

    public function cancelledRoi($eid){
            $id = decrypt($eid);
            $record = DB::table('roi')->where(['investment_id'=>$id,'status'=>'1'])->get();
            $data['investment_id'] = $eid;
            $data['roi'] = $record;
            $data['title'] = 'Return of Investment';
            $data['page']  = 'admin.investment.roi';
            $data['js'] = ['investment','validateFile'];
            return view('admin/main_layout',$data);
    }

    public function contract($eid){
        $id = decrypt($eid);
        $query = DB:: table('investments as i');
        $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')->select('u.*');
        $query->where('i.id',$id);
        $userData = $query->get();
        $data['customer_name'] = $userData[0]->fname. ' '.$userData[0]->lname; 

        $record = DB::table('contract_files')->where(['investment_id'=>$id])->get();
        $data['contract'] = $record;
        $data['title'] = 'Investment Contract';
        $data['page']  = 'admin.investment.contractList';
        $data['js'] = ['investment'];
        return view('admin/main_layout',$data);
    }
}
