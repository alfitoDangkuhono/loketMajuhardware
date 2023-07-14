@include('client.fullscren')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>CLIENT DOC</title>
    <style>
        body{
           /* background-image:{{('dist/img/pemandangan.jpeg')}} */
           background-image: url({{asset('dist/img/bg.jpg')}});
            background-size:1335px;
            /* background-width:200px; */
        }
        #title{
            color:#ffc107;
            
        }
        #nav{
            background: url(dist/img/bg-menu.jpg);
            position: relative;
            min-height: 50px;
        }
        #item{
            align-items: center;
            background-color: rgb(255, 204, 0);
            border-radius:10px;
        }
        #button{
            background-color: #000000;
            border-radius:10px;
            border: none;
        }
        #huruf{
            color: aliceblue;
            font-family: verdana;
        }
        .btn-space {
            margin-right: 5px;
        }
        .container{
            height: 200px;
            position: relative;
        }
        .vertical-center {
        margin: 0;
        position: absolute;
        top: 50%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        }
        .card{
            margin-right: 10px;
            margin-left: 22px
        }
    </style>
</head>
<body>
    {{-- <nav id="nav">
        <div class="container-fluid">
            <a class="navbar-brand" id="title">Maju Hardware</a>
           
        </div>
    </nav> --}}
    <section class="container">
        <br>
        <br>
        {{-- <div class="text-center pb-3">
            <h1 id="title">Tombol Antrian</h1>
        </div> --}}
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="container">
            <div class="row pt-5">
                <div class="card" style="width: 15rem; height:15rem;" id="item">
                    <br>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15rem" height="15rem" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                        <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                    </svg>
                    <div class="card-body" >
                        <form action="{{Route('cetak_laptop')}}" target="_blank" method="get" type="hidden"  >
                            <div class="card-body" id="button" >
                                <button id="button">     
                                    <h2 id="huruf">Laptop</h2>
                                    </button>
                                </div>
                            </div>
                        </form>
                </div> 
                <div class="card" style="width: 15rem; height:15rem;" id="item">
                    <br>
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    <div class="card-body">
                        <form action="{{Route('cetak_Gadget')}}" target="_blank" method="get" type="hidden"  >
                            <div class="card-body" id="button">
                                <button  id="button">
                                    <h2 id="huruf">Gadget</h2>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card" style="width: 15rem; height:15rem;" id="item">
                    <br>
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-pc-display-horizontal" viewBox="0 0 16 16">
                        <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0h-13Zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5ZM12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0Zm2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0ZM1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1ZM1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25Z"/>
                    </svg>
                    <div class="card-body">
                        <form action="{{Route('cetak_CPU')}}" target="blank" method="get" type="hidden"  >
                            <div class="card-body" id="button">
                                <button  id="button">
                                    <h2 id="huruf">Komputer</h2>
                                </button>
                            </div>
                        </form>
                    </div> 
                </div> 
                <div class="card" style="width: 15rem; height:15rem;" id="item">
                    <br>
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                    </svg>
                    <div class="card-body">
                        <form action="{{Route('cetak_Printer')}}" target="blank" method="get" type="hidden"  >
                            <div class="card-body" id="button">
                                <button  id="button">
                                    <h2 id="huruf"> Printer</h2>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>   
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script> 
</body>
</html>



        

