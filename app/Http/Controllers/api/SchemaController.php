<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\admin\Schema;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class SchemaController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
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
        $url = url('uploads/schema');
        $query = DB::table('schemas')->select('*');
        $query->where('deleted_at',null);
        if($request['offset'] > 0 ){
            $off= $limit * $request['offset'];
            $query->skip($off);
        }
        $query->take($limit);
        // Elq();
        $schema = $query->get();
        foreach ($schema as $key => $value) {
            $value->image = url('uploads/schema/'.$value->image);
            $value->documents = url('uploads/schema_doc/'.$value->documents);
        }
        // Plq();
        $responce = [
            'status'=>'1',
            'message'=>'Schema list',
            'data'=>$schema,
        ];
        return response()->json($responce);
    }

    public function addSchema(Request $req){
        $validator = Validator::make($req->all(), [
            'name'=>'required',
            'type'=>'required',
            'start_date'=>'required|date_format:"Y-m-d"',
            'image'=>'required|mimes:jpg,png,gif,jpeg',
            'document'=>'required|mimes:doc,docx,pdf,rtf',
            'status'=>'required',
            'details'=>'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        // dd($request->all());
        if($req->hasfile('image')){
            $file = $req->file('image');
            $ext = $file->getClientOriginalExtension();
            $image = 'image_'.time().'.'.$ext;
            $file->move(public_path('uploads/schema'),$image);
            // $image_path = $req->file('nationalIdImage')->store('kycPicture', 'public');
        }
        if($req->hasfile('document')){
            $file = $req->file('document');
            $ext = $file->getClientOriginalExtension();
            $filename = 'document_'.time().'.'.$ext;
            $file->move(public_path('uploads/schema_doc'),$filename);
          
        }

        $insertData = [
            'name' => $req['name'],
            'type' => $req['type'],
            'start_date'=> $req['start_date'],
            'status' => $req['status'],
            'details' => $req['details'],
            'image' => $image,
            'documents' => $filename,
            'created_at' => dbDateFormat(),
            'updated_at' => dbDateFormat(),
        ];
        $sta = DB::table('schemas')->insert($insertData);
        if($sta){
            $responce = [
                'status'=>'1',
                'message'=>'Record added successfully',
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'Somthing went wrong'
            ];
        }
        return response()->json($responce);
    }

    public function editSchema(Request $req){
        $validator = Validator::make($req->all(), [
            'id'=>'required|numeric',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $get = DB::table('schemas')->select('*')->where(['id'=>$req->id,'deleted_at'=>null])->get();
        foreach ($get as $key => $value) {
            $value->image = url('uploads/schema/'.$value->image);
            $value->documents = url('uploads/schema_doc/'.$value->documents);
        }
        $responce = [
            'status'=>'1',
            'message'=>'Editable Record',
            'data'=>$get
        ];
        return response()->json($responce);
    }

    public function updateSchema(Request $req){
        $validator = Validator::make($req->all(), [
            'name'=>'required',
            'type'=>'required',
            'start_date'=>'required|date_format:"Y-m-d"',
            'image'=>'mimes:jpg,png,gif,jpeg',
            'document'=>'mimes:doc,docx,pdf,rtf',
            'status'=>'required',
            'details'=>'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $getData = Schema::table('schemas')->select('*')->where(['id'=>$req->id])->get();
        if(!empty($getData->all())){
            $filename = $getData[0]->image;
            $document = $getData[0]->documents;
        }

        if($req->hasfile('image')){
            if($filename != null && file_exists(public_path('uploads/schema/'.$filename)) ){
                unlink(public_path('uploads/schema/'.$filename));
            }
            $file = $req->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = 'image_'.time().'.'.$ext;
            $file->move(public_path('uploads/schema'),$filename);
        }
        
        if($req->hasfile('document')){
            if($document != null && file_exists(public_path('uploads/schema_doc/'.$document)) ){
                unlink(public_path('uploads/schema_doc/'.$document));
            }
            $file = $req->file('document');
            $ext = $file->getClientOriginalExtension();
            $document = 'document_'.time().'.'.$ext;
            $file->move(public_path('uploads/schema_doc'),$document);
        }

        if($req ->all()){
            $id = $req['id'];
            $updateData = [
                'name' => $req['name'],
                'type' => $req['type'],
                'start_date'=> $req['start_date'],
                'status' => $req['status'],
                'details' => $req['details'],
                'image' => $filename,
                'documents' => $document,
                'updated_at' => dbDateFormat(), 
            ];
           $res = Schema::table('schemas')->where('id', $id)->update($updateData);
            if($res){
                $responce = [
                    'status'=>'1',
                    'message'=>"Record updated successfully"
                ];
            }else{
                $responce = [
                    'status'=>'0',
                    'message'=>"Somthing Went Wrong"
                ];
            }
            return response()->json($responce);
        }
    }

    function deleteSchema(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $responce = [
                'status'=>'0',
                'errors'=>$validator->errors()
            ];
            return response()->json($responce);
        }
        $id = $request->id;
        $res = Schema::where('id',$id)->delete();
        if($res){
            $responce = [
                'status'=>'1',
                'message'=>'Record deleted successfully',
            ];
        }else{
            $responce = [
                'status'=>'0',
                'message'=>'Somthing Wend Wrong'
            ];
        }
        return response()->json($responce);
    }
}
