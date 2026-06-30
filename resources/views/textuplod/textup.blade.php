@include('headadmin.headadmin')

<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,   shrink-to-fit=no">
    <title>Video upload</title>
    <style>
        #plod{
            background-color: aqua;
        }
        #head{
            background-color:grey;
        }
        #font-head{
            color: rgb(139, 196, 82);
        }
        #note{
            color: crimson;
        }
    </style>
    </head>
        <body>
            <div class="content-wrapper">
                <div class="card text-center">
                    <div class="card-header" id="head">
                        <h1 id="font-head">Input Text</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{Route('upload_txt')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div>
                                <label>Masukan Text</label>
                                <input type="text"  name="text"/>
                            </div>
                            <p>
                                @if ($errors->has('text'))
                                    {{$errors->first('text')}}
                                @endif
                            </p>
                            <button class="btn btn-primary" type="submit" name="click" >Upload</button>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        <form action="{{Route('delettext')}}" method="GET" type="hidden">
                            <button class="btn btn-danger">
                                Hapus Text
                            </button>
                        </form>
                        <h4 id="note">
                            Note: jika ingin mengganti video klik hapus Text dulu agar tidak error
                        </h4>
                    </div>
                  </div>
                  
                </div>
            
            
         
        </body>
    </html>

    @extends('headadmin.footeradmin')