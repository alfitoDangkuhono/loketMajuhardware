  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
 
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
      setInterval(function(){                                 /*SCRIPT UNTUK MEMBUAT VALUE BERGANTI/REFRESH*/
          $("#refresh").load("load");
        
      },5000);
    });
  </script>  
  <script>

  </script>
    <title>Document</title>
    <style>
      body{
        background-color: rgb(0, 0, 0);
      }
      * 
      {
        box-sizing: border-box;
      }
      /* Create three equal columns that floats next to each other */
      .column {
        float: left;
        /* width: 33.33%; */
        /* width: 50%; */
         /*width: 744px;       for microsoft edge */
         width: 771px;
        padding:0px ;
        height: 343px; 
        /* Should be removed. Only for demonstration */
      }

      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }
    #footer{
      background-color: aqua;
      /* height: 4%; */
      height: 32px;
      /* width: 100%;         for microsoft edge*/
      /* width: 100%; */
      flex-wrap: wrap;
      display: flex;
    }
    h1{
      /* font-size: 190px;  sample one*/
      font-size: 150px;
      text-align: center;
    }
    #h2{
      background: url(dist/img/bg-menu.jpg);
      color: aliceblue;
      text-align: center;
      border: 3px solid black;
      padding: 11px;
      max-width: 100%;
      border-radius: 5px;
    }
    
    .container-fluid{
        width:100%;
      
    } 
    .row{
     height: 40%;

    }
    .col-sm{
      background-color: #EDB835;
      border: 8px solid black;
      border-radius: 8px;
      height:100%;
    }
    .col-md-4{
      border: 5px solid black;
      border-radius: 8px;
      background-color: grey;
      height:151%;
    }
    .col-md-8{
      
      border-radius: 5px;
      /* height:300px; */
      height:519px;
      align-items:end;
      width:66%;
      
    }
    #video{
      /* border: 1px solid white;
      border-radius: 5px;
      height:300px;
      height:100px;
      align-items:end;
      width:976px;
      width:100px; */
    }
    #nav{
      background: url(dist/img/bg-menu.jpg);
      position: relative;
      min-height: 35px;
      text-align: center;
   
    }
    #title{
      color: yellow;
    }
    #jam{
      text-align:center;
      font-size:95px;
    }
    #img{
      align-items:center;
      width: 200px;
      height: 200px;
    }
    #btn{
      color:rgba(0,0,0,0.5);
    }
    #video{
      width:800px;
    }
    #text{
      background: url(dist/img/bg-menu.jpg);
      color: aliceblue;
      text-align: center;
      border: 3px solid black;
      padding: 11px;
      max-width: 100%;
      border-radius: 5px;
      font-size:20px;
    }
    </style>
  </head>
<body>

    <div class="container-fluid" >
      <div class="row">
        <div class="col-md-4">
        <div id="text" >MAJU CARE SERVICE CENTER </div>
        <br>
          <a id="btn" class="nav-link" data-widget="fullscreen" href="#" role="button">
          <center> <img id="img" src="dist/img/logo-mcare.png" alt=""></center>
          </a>
          <h2 id="jam"><?php print" date('H : I : S')"?></h2>
        </div>
            <video class="col-md-8" controls muted autoplay loop>
              <?php 
            include('videofunction.php');
              foreach($data as $row){ ?>
                <source src="video/<?php print"$row[video]"?>" type="video/mp4">
              <?php } ?>
            </video>
      </div>
      <div id="refresh">              <!--******* PARAMETER/DESTINATION UNTTUK MEREFERSH NO ANTRIAN******* -->
      </div>
    </div>
</body>
</html>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>



  <!-- SCRIPT JAM  -->
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


<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
 <!-- SCRIPT FULL SCREEN  -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="dist/js/adminlte.js"></script>