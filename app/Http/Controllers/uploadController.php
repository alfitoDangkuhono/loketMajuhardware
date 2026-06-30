<?php

namespace App\Http\Controllers;

use App\Models\Textdb;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;

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

        $this->clearVideoFiles();

        $file = $request->file('video');
        $fileName = $file->getClientOriginalName();
        $file->move('video', $fileName);

        Video::create([
            'video' => $fileName,
        ]);

        return redirect('uplod');
    }

    public function deleteVideo()
    {
        $this->clearVideoFiles();

        return redirect('uplod');
    }

    protected function clearVideoFiles()
    {
        foreach (Video::all() as $row) {
            $path = public_path('video/' . $row->video);
            if (file_exists($path) && is_file($path)) {
                @unlink($path);
            }
        }

        DB::table('video')->truncate();
    }

    public function uploadText(Request $request)
    {
        $request->validate([
            'text' => 'required|min:5',
        ]);

        $this->clearText();

        Textdb::create([
            'text' => $request->text,
        ]);

        return redirect('plod');
    }

    public function deleteText()
    {
        $this->clearText();

        return redirect('plod');
    }

    protected function clearText()
    {
        DB::table('text_db')->truncate();
    }
}
