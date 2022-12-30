<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;
use DataTables;
class MyinvestmentController extends Controller
{
        public function index()
        {
            $data['page'] = "users.investment_list";
            $data['js'] = ['user_investment'];
            $data['title'] = "My Investment";
            return view('admin.main_layout',$data);
        }

        public function getUserInvestmentDataTable(Request $request){
            if ($request->ajax()) {
                $Session = Session::get('admin_login');
                $query = DB:: table('investments as i');
                $query->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                        ->leftJoin('schemas as s', 's.id', '=', 'i.schema_id')
                        ->leftJoin('admins as a', 'a.id', '=', 'i.admin_id')
                        ->select('i.*', 'u.fname as customerFname','u.lname as customerLname', 's.name as schema','a.fname','a.lname');
                if($Session['role'] == '2'){
                    $id = $Session['id'];
                    $query->where('i.user_id',$id);
                }
                // Elq();
                $query->where('i.status','!=','9');
                $query->where('i.deleted_at',null);
                $data = $query->get();
                $viewData = $query->get();
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
                        $encryptedId = encrypt($row->id);
                        $view = "investmentDetails/".$encryptedId;
                        $roi = "userRoi/".$encryptedId;
                        $btn = '<a class="btn btn-warning btn-sm" href="'.url($roi).'"><i class="dw dw-eye"></i> ROI</a>
                            <a class="btn btn-success btn-sm" href="'.url($view).'"><i class="dw dw-file"></i> View</a>'; 
                            //  return $btn;
                        if($row->contract_pdf != ''){
                            $d_url = url('uploads/contract_pdf/'.$row->contract_pdf);
                                $btn .='<a href="'.$d_url.'" class="btn btn-primary btn-sm" style="margin-left: 3px;" download=""><i class="fa fa-download"></i></a>';
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

            return view('admin.main_layout');
        }

        public function investmentDetails($eid){
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
                                $start_date = strtotime($value->start_date);
                                $year = $value->tenure;
                                $end_date = date('Y-m-d', strtotime("+".$year.' month',$start_date));
                                $value->contract_end_date = $end_date;
                            }else{
                                $start_date = date('Y-m-d',strtotime($value->start_date));
                                $contract_end_date = date('Y-m-d', strtotime("+".$value->tenure." year",strtotime($start_date)));
                                $value->contract_end_date = $contract_end_date;
                            }
                        }
                        // dd($viewData);
            $data['viewData'] = $viewData;
            $data['page'] = 'users.investmentDetails';
            return view('admin.main_layout',$data);

        }


        public function userRoi($eid){
            $id = decrypt($eid);
            $roi = DB::table('roi')->where('investment_id',$id)->get();
            $data['page'] = 'users.user_roi';
            $data['js'] = []; 
            $data['roi'] = $roi; 
            $data['title'] = 'Return of investment'; 
            $data['investment_id'] = '123'; 
            return view('admin.main_layout',$data);
        }
}
