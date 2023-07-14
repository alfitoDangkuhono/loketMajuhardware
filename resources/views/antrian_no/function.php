<?php

$conn=mysqli_connect("localhost","root","","antrian_mh");

    // KODE UNTUK LAPTOP 
        $_SESSION['kode_L']='L';
        $_SESSION['jenis_L']='Laptop';
        $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='sudah' AND jenis='$_SESSION[jenis_L]' ORDER BY no_antrian DESC");
        $data=mysqli_fetch_array($hasil);
        if($data==0){
        $dt_saat_ini=0;
        }
        else{
        $dt_saat_ini=$data['cntr'];
        }
    
// KODE UNTUK GADGET
    $_SESSION['kode_G']='G';
    $_SESSION['jenis_G']='Gadget';

    $hasil_G=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='sudah' AND jenis='$_SESSION[jenis_G]' ORDER BY no_antrian DESC");
    $data_G=mysqli_fetch_array($hasil_G);
    if($data_G==0){
        $dt_A_G=0;
    }
    else{
        $dt_A_G=$data_G['cntr'];
    }

//  KODE UNTUK PRINTER 
    $_SESSION['kode_P']='P';
    $_SESSION['jenis_P']='Printer';

    $hasil_P=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='sudah' AND jenis='$_SESSION[jenis_P]' ORDER BY no_antrian DESC");
    $data_P=mysqli_fetch_array($hasil_P);
    if($data_P==0){
    $dt_A_P=0;
    }
    else{
    $dt_A_P=$data_P['cntr'];
    }

//  KODE UNTUK CPU
    $_SESSION['kode_C']='C';
    $_SESSION['jenis_C']='CPU';

    $hasil_C=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE st='sudah' AND jenis='$_SESSION[jenis_C]' ORDER BY no_antrian DESC");
    $data_C=mysqli_fetch_array($hasil_C);
    if($data_C==0){
        $dt_A_C=0;
    }
    else{
        $dt_A_C=$data_C['cntr'];
    }

?>


<div class="row fixed-bottom">
        <div class="col-sm">
          <div id="refresh">
            <h2 id="h2" >LAPTOP</h2>
            <h1  id="example"> <?php echo $_SESSION['kode_L'], sprintf("%02d",$dt_saat_ini);?></h1> 
          </div>
        </div>
        <div class="col-sm">
          <h2 id="h2">GADGET</h2>
          <h1><?php echo $_SESSION['kode_G'], sprintf("%02d",$dt_A_G);?></h1>
        </div>
        <div class="col-sm" >
          <h2 id="h2">KOMPUTER</h2>
          <h1><?php echo $_SESSION['kode_C'], sprintf("%02d",$dt_A_C);?></h1>
        </div>
        <div class="col-sm" >
          <h2 id="h2">PRINTER</h2>
          <h1><?php echo $_SESSION['kode_P'], sprintf("%02d",$dt_A_P);?></h1>
        </div>
      </div>

    
    
 
