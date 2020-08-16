<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiTodolistController extends Controller
{
    public function getList()
    {
        # code...
        $result= DB::table("todolist");//->orderBy("id","desc")->get();
        if(request('search')) {
            $result->where("content","like","%".request('search')."%");
        }
        $result = $result->orderBy("id","desc")->get();
        return response()->json($result);
    }

    public function  postCreate()    
    {
        # code...
        $content = request('content');
        DB::table('todolist')
           ->insert([
               'created_at'=>date('Y-m-d H:i:s'),
               'content'=>$content
           ]); 
        return response()->json(['status'=>true,'message'=>'Data Berhasil di tambahkan !']);
    }

    public function postUpdate($id)
    {        
        # code...
        $content = request('content');
        DB::table('todolist')
           ->where('id',$id)
           ->update([
               'updated_at'=>date('Y-m-d H:i:s'),
               'content'=>$content
           ]);
        return response()->json(['status'=>true,'message'=>'Data Berhasil di Update !']);
    }

    public function postDelete($id)    
    {
        # code...
        $content = request('content');
        DB::table('todolist')
           ->where('id',$id)           
           ->delete();
        return response()->json(['status'=>true,'message'=>'Data Berhasil di Hapus !']);
    }

    public  function getRead($id)
    {
        # code...
        $row = DB::table("todolist")->where("id",$id)->first();
        return response()->json($row);
    }
}