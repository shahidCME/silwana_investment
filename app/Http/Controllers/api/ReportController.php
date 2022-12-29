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

class ReportController extends Controller
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
            // if($request->role != '1'){
            //     $id = $request->admin_id;
            //     $query->where('i.admin_id',$id);
            // }
            if(isset($request->user_id) && $request->user_id != ''){
                $id = $request->user_id;
                $query->where('i.user_id',$id);
            }
            if((isset($request->from_start_date) &&  $request->from_start_date != '') && (isset($request->to_end_date) &&  $request->to_end_date != '')){
                $query->whereBetween('i.start_date', [dbDateFormat($request->from_start_date,true),dbDateFormat($request->to_end_date,true) ]);
            }
            $query->where('i.deleted_at',null);
            $query->orderBy('i.id','desc');
            // Elq();
            $data = $query->get();
            // Plq();
            foreach ($data as $key => $value) {
                $value->contract_pdf    = ($value->contract_pdf != '') ? url('uploads/contract_pdf/'.$value->contract_pdf) : "";
                $value->schemaImage     = ($value->schemaImage != '') ? url('uploads/schema/'.$value->schemaImage) : "";
                $value->schemaDocuments = ($value->schemaDocuments != '' ) ? url('uploads/schema_doc/'.$value->schemaDocuments) : "";
                $value->amount_in_word  = convertNumberToWord($value->amount);
                // $files = DB::table('contract_files')->where('user_id',$value->user_id)->get();
                $url = url("uploads/contract_pdf/");
                $files = DB::table('contract_files as cf')->where('cf.investment_id',$value->id)->get();
                $paymentAndSignedContract = DB::table('investment_related_files as rf')->where('rf.investment_id',$value->id)->get();
                $value->contract_files = [];
                $value->signed_contractAndPaymentFile = [];
                if(!empty($files->all())){
                    foreach ($files as $k => $v) {
                        $v->contract_pdf = $url.'/'.$v->contract_pdf; 
                    }
                    $value->contract_files = $files;
                }
                if(!empty($paymentAndSignedContract->all())){
                    foreach ($paymentAndSignedContract as $k => $v) {
                        $v->payment_reciept = url("uploads/contract_reciept/".$v->payment_reciept); 
                        $v->signed_contract_file = url("uploads/contract_reciept/".$v->signed_contract_file); 
                    }
                    $value->signed_contractAndPaymentFile = $paymentAndSignedContract;
                }
            }
            $responce = [
                'status'=>'1',
                'message'=>'Investment list',
                'data'=>$data,
            ];
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
        // if($request->all()){
            $validator = Validator::make($request->all(), [
                'investment_id'  => 'required|numeric',
                'investment_payment_file'=>'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf',
                'signed_contract_file'=>'required|mimes:jpg,png,jpeg,svg,docx,rtf,doc,pdf'
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

            if (!file_exists(public_path('uploads/signed_contract_file'))) {
                mkdir(public_path('uploads/signed_contract_file'), 0777, true);
            }
            $image2 = '';
            if($request->hasfile('signed_contract_file')){
                if (!file_exists(public_path('uploads/signed_contract_file'))) {
                    mkdir(public_path('uploads/signed_contract_file'), 0777, true);
                }
                $file = $request->file('signed_contract_file');
                $ext = $file->getClientOriginalExtension();
                $image2 = 'signed_contract_file_'.time().'.'.$ext;
                $file->move(public_path('uploads/signed_contract_file'),$image2);
            }

           $uid = $request->investment_id;
            $investData = Investment::where('id',$uid)->get();
            // dd($investData);
            $res = Investment::where('id',$uid)->update(['status'=>'1','contract_reciept'=>$image1,'updated_at'=>dbDateFormat()]);
                $files = DB::table('investment_related_files')->where('investment_id',$uid)->get();
                if(!empty($files->all())){
                    $contract_files = DB::table('contract_files')->where(['investment_id'=>$uid])->whereNotNull('terminate_date')->get();
                    if(!empty($contract_files->all())){
                        DB::table('investment_related_files')->where('investment_id',$uid)->update(['terminate_date'=>$contract_files[0]->terminate_date]);
                    }
                } 
                DB::table('investment_related_files')->insert([
                    'investment_id'=>$uid,
                    'start_date' =>$investData[0]->start_date,
                    'end_date' =>$investData[0]->contract_end_date,
                    'payment_reciept'=> $image1,
                    'signed_contract_file'=> $image2,
                    'created_at'=>dbDateFormat(),
                    'updated_at'=>dbDateFormat()
                ]);
            if($res){
                $plan = Schema::where('id',$investData[0]->schema_id)->get();
                $planName = $plan[0]->name;
                $device = Device::where(['user_id'=>$investData[0]->user_id,'role'=>'2'])->get();
                $status = " Is Approved";
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
                    // return redirect('Investment')->with('Mymessage', flashMessage('success','Investment Payment Inserted Successfully'));
                    $responce = [
                        'status'=>'1',
                        'message'=>"Investment Payment reciept/signed contract uploaded Successfully",
                    ];
                }else{
                    // return redirect('Investment')->with('Mymessage', flashMessage('danger','Something Went Wrong'));
                    $responce = [
                        'status'=>'0',
                        'message'=>"Something Went Wrong",
                    ];
                }
                return response()->json($responce);
        //  }

    }
}
