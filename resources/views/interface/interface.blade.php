<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MENU</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <style>
        body{
            background-color: rgb(205, 205, 205);
            align-items: center;
        }
        .col-sm-4 {
            margin-bottom: 20px;
            border-radius:10px;
        }
        .container{
            position: center;
            margin-top: 50px;
        }
        img{
            max-width: 100px;
        }
        .main{
            text-align: center;
        }
        .card{
          background-color: rgb(107, 157, 201);
          border-radius: 5px;
        }
        .bg_G{
          background-color: rgb(213, 55, 55);
          color: white;
          border-radius: 5px;
        }
        .bg_P{
          background-color: rgb(255, 102, 0);
          color: white;
          border-radius: 5px;
        }
        .bg_C{
          background-color: rgb(0, 153, 51);
          color: white;
          border-radius: 5px;
        }
        .bg_L{
          background-color: rgb(0, 51, 153);
          color: white;
          border-radius: 5px;
        }
        p{
          font-weight: bold;
        }
        a{
          font-weight: bold;
        }
        .bg_client{
          background-color: 	rgb(153, 153, 255);
          border-radius: 5px;
        }
        .bg_antrian{
          background-color: 	rgb(153, 255, 153);
          border-radius: 5px;
        }
  </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
              <div class="card">
                <div class="card-body">
                    <div class="main">
                        <img src="{{asset('dist/img/admin.png')}}" >
                        <p class="card-text">Klik Admin Untuk Pergi Ke Page Admin</p>
                        <a href="{{Route('login')}}" class="btn btn-primary">Admin</a>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                  <div class="bg_client">
                    <div class="card-body">
                      <div class="main">
                          <img src="{{asset('dist/img/client.jpg')}}" >
                          <p class="card-text">Klik Client Untuk Pergi Ke Client </p>
                          <a href="{{Route('client')}}" class="btn btn-primary">Client</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                  <div class="bg_antrian">
                    <div class="card-body">
                      <div class="main">
                          <img src="{{asset('dist/img/antrian.png')}}" >
                          <p class="card-text">Klik Antrian Untuk Pergi Ke Antrian </p>
                          <a href="{{Route('antri_1')}}" class="btn btn-primary">Antrian</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-sm-4">
              <div class="card">
                <div class="bg_L">
                  <div class="card-body">
                      <br>
                      <div class="main">
                          <img src="{{asset('dist/img/LAPTOP.png')}}" >
                          <p class="card-text">Klik Laptop Untuk Pergi Ke Teller Laptop</p>
                          <a href="{{Route('teler_laptop')}}" class="btn btn-primary">Laptop</a>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="card">
                <div class="bg_G">
                  <div class="card-body">
                    <div class="main">
                        <img src="{{asset('dist/img/Gadget.png')}}" >
                        <p class="card-text">Klik Gadget Untuk Pergi Ke Teller Gadget</p>
                        <a href="{{Route('teler_gadget')}}" class="btn btn-primary">Gadget</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <div class="col-sm-4">
                <div class="card">
                    <div class="bg_P">
                      <div class="card-body">
                        <div class="main">
                            <img src="{{asset('dist/img/PRINTER.png')}}" >
                            <p class="card-text">Klik Printer Untuk Pergi Ke Teller Printer</p>
                            <a href="{{Route('teler_printer')}}" class="btn btn-primary">Printer</a>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <center>
              <div class="col-sm-4">
                  <div class="card">
                    <div class="bg_C">
                      <div class="card-body">
                        <div class="main">
                            <img src="{{asset('dist/img/KOMPUTER.png')}}" >
                            <p class="card-text">Klik Komputer Untuk Pergi ke Teller Komputer </p>
                            <a href="{{Route('teler_cpu')}}" class="btn btn-primary">Komputer</a>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </center>
        </div>
    </div>
</body>
</html>