@include('headadmin.headadmin')

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,   shrink-to-fit=no">
    <title>Video upload</title>
    <style>
        #plod {
            background-color: aqua;
        }

        #head {
            background-color: grey;
        }

        #font-head {
            color: rgb(139, 196, 82);
        }

        #note {
            color: crimson;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="card text-center">
            <div class="card-header" id="head">
                <h1 id="font-head">Upload Video</h1>
            </div>
            <div class="card-body">
                <form action="{{ Route('upload') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div>
                        <label>Choose Video</label>
                        <input type="file" name="video" />
                    </div>
                    <p>
                        @if ($errors->has('video'))
                            {{ $errors->first('video') }}
                        @endif
                    </p>
                    <button class="btn btn-primary" type="submit" name="click">Upload</button>
                </form>
            </div>
            <div class="card-footer text-muted">
                <form action="{{ Route('deletVideo') }}" method="GET" type="hidden">
                    <button class="btn btn-danger">
                        Hapus Video
                    </button>
                </form>
                <h4 id="note">
                    Note: jika ingin mengganti video klik hapus video dulu agar tidak error
                </h4>
            </div>
        </div>

    </div>



</body>

</html>

@extends('headadmin.footeradmin')
