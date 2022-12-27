<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\admin\Investment;
use App\Models\admin\User;
use App\Models\admin\Schema;
use App\Models\Device;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDF;
use Stichoza\GoogleTranslate\GoogleTranslate;

class InvestmentController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'admin_id'=> 'required',
            'offset'  => 'required',
            'role'    => 'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $limit = 10;
        $offset = 0;
        $query = DB:: table('investments as i');
              $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                    ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname','s.image as schemaImage','s.documents as schemaDocuments');
            if($request['offset'] > 0 ){
                $off= $limit * $request['offset'];
                $query->skip($off);
            }
            $query->take($limit);
            if($request->role != '1'){
                $id = $request->admin_id;
                $query->where('i.admin_id',$id);
            }
            if($request->role == '2'){
                $id = $request->admin_id;
                $query->where('i.user_id',$id);
            }
            $query->where('i.deleted_at',null);
            $query->orderBy('i.id','desc');
            $data = $query->get();
            foreach ($data as $key => $value) {
                $value->contract_pdf    = ($value->contract_pdf != '') ? url('uploads/contract_pdf/'.$value->contract_pdf) : "";
                $value->schemaImage     = ($value->schemaImage != '') ? url('uploads/schema/'.$value->schemaImage) : "";
                $value->schemaDocuments = ($value->schemaDocuments != '' ) ? url('uploads/schema_doc/'.$value->schemaDocuments) : "";
                $value->amount_in_word  = convertNumberToWord($value->amount);
                // $files = DB::table('contract_files')->where('user_id',$value->user_id)->get();
                $url = url("uploads/contract_pdf/");
                $files = DB::table('contract_files as cf')->where('cf.investment_id',$value->id)->get();
                $value->contract_files = [];
                if(!empty($files->all())){
                    foreach ($files as $k => $v) {
                        $v->contract_pdf = $url.$v->contract_pdf; 
                    }
                    $value->contract_files = $files;
                }
            }
            $responce = [
                'status'=>'1',
                'message'=>'Investment list',
                'data'=>$data,
            ];
            return response()->json($responce);
    }

    public function add(Request $req){
        $validator = Validator::make($req->all(), [
            'admin_id'=> 'required',
            'customer_id' => 'required',
            'schema_id' => 'required',
            'amount' => 'required',
            'return_type'=>'required',
            'tenure' => 'required',
            'return_percentage' => 'required',
            'start_date' => 'required',
            'status' => 'digits_between:0,2'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }

        if($req->all()){
       
            $image1 ='';
            $investDoc ='';
            $otherDoc ='';
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
            $res->status = ($req->status == '' ) ? '2' : $req->status; 
            if($req->role == '0' || $req->role == '3'){
                $res->status = '2';
            }
            $res->admin_id = $req->admin_id;
            $res->user_id = $req->customer_id; 
            $res->schema_id = $req->schema_id;
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
                $responce = [
                    'status'=>'1',
                    'message' =>"Record Inserted Successfully"
                ];
                return response()->json($responce);  
            }
        }
    }

    function delete(Request $req) {
        $validator = Validator::make($req->all(), [
            'id'=> 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $req->id;
        $res = Investment::find($id);
        if(!is_null($res)){
            $res->delete();
            $responce = [
                'status'=>'1',
                'message' =>"Record Deleted Successfully"
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message' =>"Something Went Wrong"
            ];
        }
        return response()->json($responce);

    }

    public function edit(Request $req){
        $validator = Validator::make($req->all(), [
            'id'=> 'required',
            'role'=>'required|numeric',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $req->id;
        $res =  Investment::find($id);
        // dd($res);
        if(!empty($res)){
            $res['contract_pdf'] = url('public/uploads'.$res['contract_pdf']);
            $res['customer_id'] = $res['user_id'];
            $res['contract'] = getContractTemplate();
        }
        $responce = [
            'status'=>'1',
            'message'=>'edit data',
            'data' =>($res != '') ? $res : '', 
        ];
        return response()->json($responce);
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'=> 'required',
            'role'=>'required|numeric',
            'admin_id'=> 'required',
            'customer_id' => 'required',
            'schema_id' => 'required',
            'amount' => 'required',
            'return_type'=>'required',
            'tenure' => 'required',
            'return_percentage' => 'required',
            'start_date' => 'required||date_format:"Y-m-d"',
            'status' => 'digits_between:0,2',
            'contract'=>'required',
            'lang'=>'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }

            $investData = Investment::where('id',$req->investment_id)->get();
            
            if($req->hasfile('contract_reciept')){
                if($filename != NULL && file_exists(public_path('uploads/contract_reciept/'.$filename)) ){
                    unlink(public_path('uploads/contract_reciept/'.$filename));
                }
                $file = $req->file('contract_reciept');
                $ext = $file->getClientOriginalExtension();
                $filename = 'contract_reciept_'.time().'.'.$ext;
                $file->move(public_path('uploads/contract_reciept'),$filename);
            }else{
                $filename = $investData[0]->contract_reciept;
            }
            
            

            if($req->hasfile('invest_document')){
                if($invest_document != NULL && file_exists(public_path('uploads/invest_document/'.$invest_document)) ){
                    unlink(public_path('uploads/invest_document/'.$invest_document));
                }
                $file = $req->file('invest_document');
                $ext = $file->getClientOriginalExtension();
                $invest_document = 'invest_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/invest_document'),$invest_document);
            }else{
                $invest_document = $investData[0]->investment_doc;
            }


            if($req->hasfile('other_document')){
                if($other_document != NULL && file_exists(public_path('uploads/other_document/'.$other_document)) ){
                    unlink(public_path('uploads/other_document/'.$other_document));
                }
                $file = $req->file('other_document');
                $ext = $file->getClientOriginalExtension();
                $other_document = 'other_document_'.time().'.'.$ext;
                $file->move(public_path('uploads/other_document'),$other_document);
            }else{
                $other_document = $investData[0]->other_doc;
            }
            $id = $req->investment_id;
            $updateData = [
                'user_id' => $req->customer_id,
                'schema_id'=> $req->schema_id,
                'tenure'=> $req->tenure,
                'start_date'=> dbDateFormat($req->start_date,true),
                'amount' => $req->amount,
                'return_type' => $req->return_type,
                'return_percentage' => $req->return_percentage,
                'status' => (isset($req->status)) ? $req->status : '2',
                'contract_reciept' => $filename,
                'investment_doc' => $invest_document,
                'other_doc' => $other_document,
                'updated_at' => dbDateFormat(), 
            ];
            // dd($updateData);
            // $investData = Investment::where('id', $id)->get();
            $result = Investment::where('id', $id)->update($updateData);
            
            
            $availableStartData = $investData[0]->start_date;

            
            DB::table('roi')->where(['investment_id' =>$id,'status'=>'0'])->delete(); 

            $roi = DB::table('roi')->where(['investment_id' =>$id,'status'=>'1'])->get()->toArray();
            $completedRecord = count($roi);

            if($availableStartData != $req->start_date){
                $completedRecord = 0;
            }


            for ($i=1; $i <= ($req->tenure-$completedRecord)  ; $i++) { 

            if($availableStartData == $req->start_date){

                if(!empty($roi)){
                    $date_of_return = $roi[$completedRecord-1]->date_of_return;
                    $continueFrom = strtotime($date_of_return);
                }else{
                    $continueFrom = strtotime(dbDateFormat($req->start_date,true));
                }
            }else{
                    $continueFrom = strtotime(dbDateFormat($req->start_date,true));
            }

            if($req->return_type == '0'){
                $final_date = date("Y-m-d", strtotime("+".$i." month", $continueFrom));
            }else{
                $final_date = date('Y-m-d', strtotime("+".$i." years",$continueFrom));
            } 

                $returnAmount = ($req->amount * $req->return_percentage)/100;
                $willInsert = [
                    'investment_id'=>$id,
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
            if(	$req->status !='0'){
                // dd($req->all());
                if (!file_exists(public_path('uploads/contract_pdf'))) {
                    mkdir(public_path('uploads/contract_pdf'), 0777, true);
                }
                // if($investData[0]->contract_pdf != '' && file_exists(public_path('uploads/contract_pdf/'.$investData[0]->contract_pdf))){
                //     unlink(public_path('uploads/contract_pdf/'.$investData[0]->contract_pdf));
                // }
                // $id = decrypt($req->update_id);
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
                        $data['viewData'][0]->contract_start_date = $req->start_date;
                        $data['viewData'][0]->contract_end_date = $end_date;
                    }else{
                        $start_date = strtotime('Y-m-d',$req->start_date);
                        $contract_end_date = date('Y-m-d', strtotime("+".$req->tenure." year",$start_date));
                        $data['viewData'][0]->contract_start_date = $req->start_date;
                        $data['viewData'][0]->contract_end_date = $contract_end_date;
                    }
                    // $contractEndDate = 
                    $contractStartDateFraction = explode("-",date("d-F-Y",$start_date));
                    $data['viewData'][0]->day   =  $day = $contractStartDateFraction[0];
                    $data['viewData'][0]->month =  $month = $contractStartDateFraction[1];
                    $data['viewData'][0]->year  =  $year = $contractStartDateFraction[2];
                    $viewData[0]->amountArabic = convertNumberToWord($viewData[0]->amount);
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
                        $data['arabic']['amount'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->amount);
                        $data['arabic']['contract_end_date'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->contract_end_date);
                        $data['arabic']['contract_start_date'] = $tr->setSource('en')->setTarget('ar')->translate($viewData[0]->contract_start_date);
                    }
                    // dd($req->contract);
                    $pdf = PDF::loadView('admin.contractTemplate.'. strtolower($req->contract), $data);
                    $filename = strtolower($req->contract).'_'.time().'.pdf';
                    $pdf->save(public_path('uploads/contract_pdf/').$filename);



                    $availableAmount = $investData[0]->amount;
                    $availableStartData = $investData[0]->start_date;
                    if(($investData[0]->contract_pdf == NULL) ||( ($availableAmount != $req->amount) || ($availableStartData != dbDateFormat($req->start_date,true)))){
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
                        // echo '2';die;
                        $willUpdateFiles = DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date])->get();
                        // dd($willUpdateFiles->all());
                        if(!empty($willUpdateFiles->all())){
                            if(file_exists(public_path('uploads/contract_pdf/'.$willUpdateFiles[0]->contract_pdf))){
                                unlink(public_path('uploads/contract_pdf/'.$willUpdateFiles[0]->contract_pdf));
                            }
                        } 
                        if(!empty($willUpdateFiles->all())){
                            DB::table('contract_files')->where(['investment_id'=>$investData[0]->id,'start_date'=>$investData[0]->start_date])->update(
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
                if(	$req->status=='1'){
                    $this->insertNotification($insertData);
                }
            }
            if($result){
                $responce = [
                    'status'=>'1',
                    'message' =>'Record updated successfully',
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message' =>'Somthing Went Wrong', 
                ];
            }
            return response()->json($responce);
    }

    public function getRoi(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'=> 'required|numeric',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $record = DB::table('roi')->where('investment_id',$req->investment_id)->get();
        foreach ($record as $key => $value) {
            if($value->payment_trasfer_reciept != ''){
                $value->payment_trasfer_reciept = url('public/uploads/payment_trasfer_reciept'.$value->payment_trasfer_reciept);
            }else{
                $value->payment_trasfer_reciept = "";
            }
        }
        if(count($record) > 0){
            $responce = [
                'status'=>'1',
                'message'=>"Return of Investment",
                'data' =>$record,
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>"No Record Found"
            ];
        }
        return response()->json($responce);
        
    }


    public function customerAndSchemaList(){
        
        $customerList = User::get();
        $customerList->makeHidden(['password']);
        $schemaList = Schema::get();
        $responce = [
            'status'=>'1',
            'message' =>"customerAndschemaList",
            'data'=>[ 'customer_list' =>$customerList, 'schema_list' =>$schemaList] 
        ];
        return response()->json($responce);

    }

    public function investmentDetails(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'=> 'required|numeric',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $req->investment_id;
        $query = DB:: table('investments as i');
                $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                    ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname','s.details');
              $query->where('i.id',$id);
              $query->where('i.deleted_at',null);
             $viewData = $query->get();
        if(count($viewData)> 0){
            if($viewData[0]->contract_pdf != ''){
                $viewData[0]->contract_pdf = url('public/uploads/contract_pdf/'.$viewData[0]->contract_pdf);
            }else{
                $viewData[0]->contract_pdf = "";
            }
            $responce = [
                'status'=>'1',
                'message'=>"Investment Details",
                'data' =>$viewData,
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>"No Record Found"
            ];
        }
        return response()->json($responce);
    }

    public function paymentReciept(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'=> 'required|numeric',
            'roi_id'=> 'required|numeric',
            'payment_trasfer_reciept'=>'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf'
        ]);
        // dd($req->all());
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        // dd($req->all());
        if (!file_exists(public_path('uploads/payment_trasfer_reciept'))) {
            mkdir(public_path('uploads/payment_trasfer_reciept'), 0777, true);
        }
        if($req->hasfile('payment_trasfer_reciept')){
            $file = $req->file('payment_trasfer_reciept');
            $ext = $file->getClientOriginalExtension();
            $fileName = 'payment_trasfer_reciept_'.time().'.'.$ext;
            $file->move(public_path('uploads/payment_trasfer_reciept'),$fileName);
        }
        $result = DB::table('roi')->where('id',$req->roi_id)->update(['payment_trasfer_reciept'=>$fileName,'status'=>'1','updated_at'=>dbDateFormat()]);
        
        if($result){
            $investment_id = $req->investment_id;
            $investment = DB::table('investments')->where('id',$investment_id)->get();
            $returnType = ($investment[0]->return_type == '0') ? 'Monthly' : 'Yearly';
            $schema = schema::where('id',$investment[0]->schema_id)->get();
                $user = USER::where('id',$investment[0]->user_id)->get();
                $fname = $user[0]->fname;  
                $title = $returnType.' Return of '.$fname;
                $message = $returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y');
                $device = Device::where(['user_id'=>$investment[0]->admin_id,'role'=>'1'])->get();

                if(!empty($device->all())){
                    $notification_id = $device[0]->token;
                    $id = $investment[0]->admin_id;
                    $type = $device[0]->type;
                    send_notification_FCM($notification_id, $title, $message, $id,$type);
                }

                $insertData = ['for_role'=>'1','user_id'=>$investment[0]->admin_id,'title'=>$returnType.' Return of '.$fname,'description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y'),'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                $this->insertNotification($insertData);
                
                // for user
                $title = $returnType.' Return of '.$fname;
                $message = $returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y');;
                $device = Device::where(['user_id'=>$investment[0]->user_id,'role'=>'2'])->get();
                if(!empty($device->all())){
                    $notification_id = $device[0]->token;
                    $id = $investment[0]->admin_id;
                    $type = $device[0]->type;
                    send_notification_FCM($notification_id, $title, $message, $id,$type);
                }
                $insertData = ['for_role'=>'1','user_id'=>$investment[0]->admin_id,'title'=>$returnType.' Return','description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on '.date('d F Y'),'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                $this->insertNotification($insertData);

                // $insertData = ['for_role'=>'1','user_id'=>$investment[0]->user_id,'title'=>$returnType.' Return of '.$fname,'description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on ','created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                // $this->insertNotification($insertData);
                // $insertData = ['for_role'=>'2','user_id'=>$investment[0]->user_id,'title'=>$returnType.' Return','description'=>$returnType.' return of investment in '.$schema[0]->name.' is transferred on ','created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                // $this->insertNotification($insertData);
                
                $responce = [
                    'status'=>'1',
                    'message'=>"File Submitted Successfully",
                ];
        }else{
                $responce = [
                    'status'=>'0',
                    'message'=>"Something Went Wrong"
                ];
        }
            return response()->json($responce);

    }

    public function contractCancel(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'=> 'required|numeric',
            'contractCancelComment'=> 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $uid = $req->investment_id;
        $res = Investment::where('id',$uid)->update(['status'=>'9','contractCancelComment'=>$req->contractCancelComment,'updated_at'=>dbDateFormat()]);
        if($res){
            $responce = [
                'status'=>'1',
                'message'=>"Investment Canceled Successfully",
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>"Something Went Wrong",
            ];
        }
        return response()->json($responce);
    }

    public function cancelledInvestment(Request $req){
        $validator = Validator::make($req->all(), [
            'role'=> 'required|numeric',
            'id'  => 'required|numeric'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $limit = 10;
        $query = DB:: table('investments as i');
              $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                    ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                    ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                    ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname');
            if($req->role == '0'){
                $id = $req->id;
                $query->where('i.admin_id',$id);
            }
            if($req->offset > 0 ){
                $off= $limit * $req->offset;
                $query->skip($off);
            }
            $query->take($limit);
            $query->where('i.deleted_at',NULL);
            $query->where('i.status','9');
            $query->orderBy('id','desc');
            $record = $query->get();
            foreach ($record as $key => $value) {
                $value->contract_pdf    = ($value->contract_pdf != '') ? url('uploads/contract_pdf/'.$value->contract_pdf) : "";
            }
            // dd($record);
            if($record){
                $responce = [
                    'status'=>'1',
                    'message'=>"Cancelled Investment",
                    'data' => $record
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>"No data found",
                ];
            }
            return response()->json($responce);
    }

    public function cancelledRoi(Request $req){
        $validator = Validator::make($req->all(), [
            'investment_id'  => 'required'
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $record = DB::table('roi')->where(['investment_id'=>$req->investment_id,'status'=>'1'])->get();
        if(!empty($record->all())){
            $responce = [
                'status'=>'1',
                'message'=>"Cancelled Roi",
                'data' => $record
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>"No data found",
            ];
        }
        return response()->json($responce);
    }

    public function payment_reciept(Request $request){
        if($request->all()){
            $validator = Validator::make($request->all(), [
                'investment_id'  => 'required|numeric',
                'investment_payment_file'=>'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf'
            ]);
            if ($validator->fails()) {
                $responce = [
                    'status'=>'0',
                    'errors'=>$validator->errors()
                ];
                return response()->json($responce);
            }

            $image1 ='';
            if($request->hasfile('investment_payment_file')){
                if (!file_exists(public_path('uploads/contract_reciept'))) {
                    mkdir(public_path('uploads/contract_reciept'), 0777, true);
                }
                $file = $request->file('investment_payment_file');
                $ext = $file->getClientOriginalExtension();
                $image1 = 'investment_payment_file_'.time().'.'.$ext;
                $file->move(public_path('uploads/contract_reciept'),$image1);
            }
            $uid = $req->investment_id;

            $investData = Investment::where('id',uid)->get();
            
            $res = Investment::where('id',$uid)->update(['status'=>'1','contract_reciept'=>$image1,'updated_at'=>dbDateFormat()]);
           
            if($res){
                $plan = Schema::where('id',$investData[0]->schema_id)->get();
                $planName = $plan[0]->name;
                $device = Device::where(['user_id'=>$investData[0]->user_id,'role'=>'2'])->get();
                if(!empty($device->all())){
                    $notification_id = $device[0]->token;
                    $title = $planName;
                    $message =  'Application for Investment Plan '.$planName.' '.$status;
                    $id = $investData[0]->user_id;
                    $type = $device[0]->type;
                    send_notification_FCM($notification_id, $title, $message, $id,$type);
                }
                    $message =  'Application for Investment Plan '.$planName.' '.$status;
                    $insertData = ['for_role'=>'2','user_id'=>$investData[0]->user_id,'title'=>$planName,'description'=>$message,'created_at'=>dbDateFormat(),'updated_at' => dbDateFormat()];
                    $this->insertNotification($insertData);
                    return redirect('Investment')->with('Mymessage', flashMessage('success','Investment Payment Inserted Successfully'));
                    $responce = [
                        'status'=>'1',
                        'message'=>"Investment Payment Inserted Successfully",
                    ];
                }else{
                    return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
                    $responce = [
                        'status'=>'0',
                        'message'=>"Something Went Wrong",
                    ];
                }
                return response()->json($responce);
         }

    }
}
