<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoModel;

class TodoController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function AddTask(Request $request){
        
        $checkDup = TodoModel::where('delete_status',0)->where('task_status',0)->where('name',$request->name)->first();
        if($checkDup){
            return response()->json([
                'status'=>201,
                'message'=>'Task Already added'
            ]);
        }
        else{
            $data = new TodoModel();
            $data->name = $request->name;
            $res = $data->save();
            if($res){
                return response()->json([
                    'status'=>200,
                    'message'=>'Task Add',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status'=>402,
                    'message','Task Add'
                ]);
            }
        }

    }

    public function GetTodo(){

        $todo = TodoModel::where('delete_status',0)->where('task_status',0)->get();
        return response()->json([
            'status'=>200,
            'data'=>$todo
        ]);
    }


    public function DeleteData(Request $request)
    {
        $data = TodoModel::findOrFail($request->id);
        if($data){
            $data->delete_status = 1;
            $del = $data->save();
            if($del){
                return response()->json([
                    'status'=>200,
                    'message'=>'Data Delete'
                ]);
            }
            else{
                return response()->json([
                 'status'=>302,
                 'message'=>'Something Went Wrong'
                ]);
            }
        }
        else{
            return response()->json([
             'status'=>402,
             'message'=>'Data Not Found'
            ]);
        }

    }
    public function UpdateTask(Request $request){

        $data = TodoModel::findOrFail($request->id);
        if($data){
            $data->task_status = 1;
            $del = $data->save();
            if($del){
                return response()->json([
                    'status'=>200,
                    'message'=>'Task Done'
                ]);
            }
            else{
                return response()->json([
                 'status'=>302,
                 'message'=>'Something Went Wrong'
                ]);
            }
        }
        else{
            return response()->json([
             'status'=>402,
             'message'=>'Data Not Found'
            ]);
        }
    }

    public function ShowAllTask(){

        $showalltask = TodoModel::where('delete_status',0)->get();
        if($showalltask ){
            return response()->json([
                'status'=>200,
                'data'=>$showalltask
            ]);
        }
        else{
            return response()->json([
                'status'=>402,
                'data'=>'Not went Wrong'
            ]);
        }
    }
}
