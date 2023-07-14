@include('headadmin.headadmin')

<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,   shrink-to-fit=no">
    <title>Video upload</title>
    </head>
        <body>
            <div class="content-wrapper">
                <div class="content">
                    <h1>Upload a video</h1>
                    <div class="">
                        <form action="{{Route('upload')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div >
                                <label>Choose Video</label>
                                <input type="file"  name="video"/>
                            </div>
                            <button type="submit" name="click" >Submit</button>
                            <p>
                                @if ($errors->has('video'))
                                    {{$errors->first('video')}}
                                @endif
                            </p>
                        </form>
                    </div>
                    <div>
                        <p>
                            Note: jika ingin mengganti video klik hapus video dulu agar tidak error
                        </p>
                        <form action="{{Route('deletVideo')}}" method="GET" type="hidden">
                            <button>
                                Hapus Video
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </body>
    </html>

    @extends('headadmin.footeradmin')