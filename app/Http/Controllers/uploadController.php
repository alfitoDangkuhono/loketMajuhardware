<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\video;
use Illuminate\Support\Facades\DB;



class uploadController extends Controller
{
    public function  upload_Page(){
        return view('uploadVideo/uploadvideo');
    }

    public function video_uplod(Request $request){
        $request->validate([
            'video' => 'required|mimes:mp4,mp3,ogx,oga,ogv,ogg,webm'
      ]);
        $file=$request->file('video');
        $file->move('video',$file->getClientOriginalName());
        $file_name=$file->getClientOriginalName();

        $insert=new video();
        $insert->video=$file_name;
        $insert->save();
        return redirect('uplod');
     }
     public function deletvidio(){
        DB::table('video')->truncate();                                          // -----> AUTOMATIC CODE  QUERY DELETE/RESET VIDEO <------- \\
        return redirect('uplod');
     }

}