<?php

namespace App\Http\Controllers;

use App\Models\Textdb;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    public function videoPage()
    {
        return view('uploadVideo/uploadvideo');
    }

    public function textPage()
    {
        return view('textuplod/textup');
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mp3,ogx,oga,ogv,ogg,webm',
        ]);

        $file = $request->file('video');
        $file->move('video', $file->getClientOriginalName());

        Video::create([
            'video' => $file->getClientOriginalName(),
        ]);

        return redirect('uplod');
    }

    public function deleteVideo()
    {
        DB::table('video')->truncate();

        return redirect('uplod');
    }

    public function uploadText(Request $request)
    {
        $request->validate([
            'text' => 'required|min:5',
        ]);

        Textdb::create([
            'text' => $request->text,
        ]);

        return redirect('plod');
    }

    public function deleteText()
    {
        DB::table('text_db')->truncate();

        return redirect('plod');
    }
}
