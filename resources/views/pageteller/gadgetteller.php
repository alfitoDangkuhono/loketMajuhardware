<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     
    <title>Document</title>
</head>
<style>
  body{
      background-color: #74797b;
    }
    #title{
      color: rgb(235, 243, 243)
    }
    .card{
      border: 2px solid black;
      width: 50%;
      align-content: center;
    }
    #no_mendatang{
      width: 50%;
    }
    #main{
      border: 2px solid black;
    }
    #title_main{
      background-color: black;
      border-radius: 10px;
    }
    #tombol{
      top:11%;
       left:55%;
       width:100px;
       height:40px;
       position: end;
       z-index: 2;
    }

    .dropbtn {
    /* background-color: #04AA6D; */
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    background-color:rgba(0,0,0,0.5);
    /* background-color:#997a00; */
    border-radius:15px 50px 30px 5px;;
    }

  .dropdown {
    position: relative;
    display: inline-block;
    
    }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 10px;
    
  }

  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }
  #jam{
    color:white;
    border-radius:3px ;
  }

  .dropdown-content a:hover {background-color: #ddd;}

  .dropdown:hover .dropdown-content {display: block;}

  .dropdown:hover .dropbtn {background-color: #666699;}
</style> 

<?php
  session_start();
  $_SESSION['kode']='G';
  $_SESSION['halaman']="uknown_9";
  $_SESSION['loket']='Gadget';
  $_SESSION['jenis']='Gadget';

  $conn=mysqli_connect("localhost","root","","antrian_mh");
  $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='' AND jenis='Gadget' ORDER BY id asc");
  $data=mysqli_fetch_array($hasil);

  if ($data == 0) {
    $no=0;
    $mess="********* NO ANTRIAN BELUM MASUK *********";
  }
  
  else {  
    $no=$data['no_antrian'];
    $mess="";
    
  }
  
  include "function.php";
?>

<body >
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
            <a class="navbar-brand" >Loket Gadget</a>
            <a id="jam"><?php echo date('H : I : S')?></a>
    </div>
  </nav>
  <center>
    <div class="card-body">
      <br>
      <br>
      <br>
      <div class="card">
        <div class=" navbar-dark bg-dark">
          <center><h1 style="color:aliceblue">Loket Gadget</h1></center>
        </div>
        <center><h3><?php print"$mess"?></h3></center>
        <strong>
         <h1 class="card-body" id="font"><?php echo $_SESSION['kode']," ", sprintf("%02d",$no); ?> </h1>
        </strong>
        <div class="d-flex justify-content-center">
          <div class="grid gap-0 row-gap-3">
              <button onclick="panggil()"  type="button" class="btn btn-warning" ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone-fill" viewBox="0 0 16 16">
                <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-11zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25.222 25.222 0 0 1 1.088.085c2.053.204 4.038.668 5.912 1.56V3.224zm-8 7.841V4.934c-.68.027-1.399.043-2.008.053A2.02 2.02 0 0 0 0 7v2c0 1.106.896 1.996 1.994 2.009a68.14 68.14 0 0 1 .496.008 64 64 0 0 1 1.51.048zm1.39 1.081c.285.021.569.047.85.078l.253 1.69a1 1 0 0 1-.983 1.187h-.548a1 1 0 0 1-.916-.599l-1.314-2.48a65.81 65.81 0 0 1 1.692.064c.327.017.65.037.966.06z"/>
              </svg> Panggil</button>
              &nbsp;
              &nbsp;
            <button type="button" onclick="next_no()" class="btn btn-success">Antrian Selanjutnya <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg></button>
          </div>
        </div>
      </div>
    </div>
  </center>
  <br>
  <br>
  <?php
     $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='' AND jenis='Gadget' ORDER BY no_antrian asc");
     $data=mysqli_fetch_array($hasil);
 
     $_SESSION['kode_G']='G';
     if($data==0){
       $no_dt_belum=0;
     }
     else{
       $no_dt_belum=$data['cntr'];
     }
  ?>
  <?php
    $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='sudah' AND jenis='Gadget' ORDER BY no_antrian desc"); 
    $data=mysqli_fetch_array($hasil);
    $_SESSION['kode_G']='G';  
    
    if($data==0){
      $no_dt_sudah=0;
    }
    else{
      $no_dt_sudah=$data['cntr']-1;
    }
  ?>
    <div class="container-fluid">
      <div class="grid gap-0 row-gap-3">
        <div class="row">
          <div class="col-sm-3 col-md-6 bg-warning " id="main">
            <div id="title_main">
              <center><h1 style="color:aliceblue">No mendatang</h1></center>
            </div>
            <br>
            <strong>
              <center><h1 class="card-body" id="font" alt="..."><?php echo $_SESSION['kode_G']," ",sprintf("%02d",$no_dt_belum)  ?></h1></center>
            </strong>
          </div>
          <div class="col-sm-9 col-md-6 bg-success" id="main" >
            <div id="title_main">
              <center><h1 style="color:rgb(255, 247, 247)">No selesai</h1></center>
            </div>
            <br>
            <strong>
              <center><h1 class="card-body" id="font" alt="..."><?php echo $_SESSION['kode_G']," ",sprintf("%02d",$no_dt_sudah)  ?></h1></center>
            </strong>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>

      <!-- SCRIPT FULL SCREEN  -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="dist/js/adminlte.js"></script>


<script type="text/javascript">
  window.onload = function() { jam(); }
 
  function jam() {
      var e = document.getElementById('jam'),
      d = new Date(), h, m, s;
      h = d.getHours();                                               
      m = set(d.getMinutes());
      s = set(d.getSeconds());
 
      e.innerHTML = h +':'+ m +':'+ s;
 
      setTimeout('jam()', 1000);
      if(m>1){
        
      }
  }
  function set(e) {
      e = e < 10 ? '0'+ e : e;
      return e;
  }
</script>




