<?php
    // CODE NO-ANTRIAN
    $conn=mysqli_connect("localhost","root","","antrian_mh");
    $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE jenis='CPU'");
    $data=mysqli_num_rows($hasil);
    $maxkode=$data +1;
    $result_code=sprintf("%02d",$maxkode);
    
    //VARIABLE SUPPORT DATABASE
    $huruf='C';
    $jenis="CPU";
    $status="";
    $tgl=now()->toDateTimeString();
    $waktu=date('H:i');

     // COUNTER CODE
     $data_C=mysqli_num_rows($hasil);
     $cd=$data_C +1;
     $cd_C=$cd; 
     $cntr=$cd_C;
    
    $input=mysqli_query($conn,"INSERT INTO table_no_antrian(no_antrian,huruf,jenis,st,tgl,waktu,cntr)VALUES ('$result_code','$huruf','$jenis','$status','$tgl','$waktu','$cntr')");
    if($input){
    print"<script>no_antrian=$result_code&huruf=$huruf&jenis=$jenis&st=$status&tgl=$tgl&waktu=$waktu'</script> ";
    }    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.</title>
    <style>
        h3{
                font-size:30px;
            }
            .content{
                width:100%;
                text-align:center;
            }
            img{
                width:40px;
                height:40px;
                float: left;
                margin-left:200px
            }
            #kode{
                font-size:30px;
            }
            #text{
                margin-right:240px;
            }
    </style>
</head>
<body>
<div class="content">
        <p id="text"><img src="../dist/img/logo-mcare.png"/><u>MAJU CARE SERVICE CENTER</u><br>Jl.Pahlawan No. 38-40, Kota Madiun <br><?php print"Loket : $jenis"?><br><p id="kode"><?php print"$huruf $result_code"?></p></p>
        <p ><?php print $tgl?><br>TERIMA KASIH <br> ATAS KUNJUNGAN ANDA</p>
        <p> </p>
    </div>
<!-- <script>
    cut();
</script> -->
<script type="text/javascript">
    window.print();
    window.onfocus=function(){window.close();}
    window.onmousemove=function(){window.close();}
</script>
</body>
</html>


