<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\admin\Investment;
use App\Models\admin\Admin;
use App\Models\admin\User;
use App\Models\admin\Schema;
use DB;
class DashboardController extends Controller
{
        public function index(Request $request){
            $validator = Validator::make($request->all(), [
                'role'    => 'required'
            ]);
            if ($validator->fails()) {
                $responce = [
                    'status'=>'0',
                    'errors'=>$validator->errors()
                ];
                return response()->json($responce);
            }

            if($request->role == '1' || $request->role == '0' || $request->role == '3' || $request->role == '4' || $request->role == '2'){

                if($request->role == '1' || $request->role == '0' || $request->role == '3' || $request->role == '4'){
                    $data['totalInvestment'] = Investment::where('status','!=','9')->sum('amount');
                    $data['totalUser'] = User::count();
                    $data['totalSalePerson'] = Admin::where('role','0')->count();
                    $data['totalFinancePerson'] = Admin::where('role','3')->count();
                    $data['totalApprover'] = Admin::where('role','4')->count();
                    $data['totalSchema'] = Schema::count();
                    $data['cancelledInvestment'] = Investment::where('status','9')->count();;

                }else{
                    $validator = Validator::make($request->all(), [
                        'role'    => 'required|numeric',
                        'user_id' => 'required|numeric'
                    ]);
                    if ($validator->fails()) {
                        $responce = [
                            'status'=>'0',
                            'errors'=>$validator->errors()
                        ];
                        return response()->json($responce);
                    }
                    $user_id = $request->user_id;
                    $data['totalInvestment'] = Investment::where('user_id',$user_id)->sum('amount');
                    $data['numberOfInvestment'] = Investment::where('user_id',$user_id)->count();
                    $data['totalReturn'] = DB::table('investments as i')
                    ->join('roi as r', 'i.id', '=', 'r.investment_id')
                    ->where(['i.user_id'=>$user_id,'r.status'=>'1'])->sum('r.return_amount');
                }
                
                $responce = [
                    'status'=>'1',
                    'message'=>"Dashboard Data",
                    'data'=> $data
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>"No data Available",
                ];
            }
            return response()->json($responce);

        }
    
        public function getNotification(Request $request){
            $validator = Validator::make($request->all(), [
                'role'    => 'required|numeric',
                'user_id' => 'required|numeric',
                'offset'  => 'required'
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
            $query = DB::table('notifications')->select('*');
            $query->where(['for_role'=>$request->role,'user_id'=>$request->user_id]);
            if($request['offset'] > 0 ){
                $off= $limit * $request['offset'];
                $query->skip($off);
            }
            $query->take($limit);
            $query->orderBy('id','desc');
            // Elq();
            $notification = $query->get();
            // Plq();
            $responce = [
                'status'=>'1',
                'message'=>'Notification list',
                'data'=>$notification,
            ];
            return response()->json($responce);
            // dd($salesPerson);
        }
}
