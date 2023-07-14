<?php
       // NO-ANTRIAN CODE 
    $conn=mysqli_connect("localhost","root","","antrian_mh");
    $hasil=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE jenis='Laptop'");
    $data=mysqli_num_rows($hasil);
    $maxkode=$data +1;
    $result_code_L=sprintf("%02d",$maxkode);
    $huruf_L='L';
    $jenis="Laptop";
    $status="";
    $tgl=now()->toDateTimeString(); 
    $waktu=date(' H:i ');

        // COUNTER CODE
    $data_L=mysqli_num_rows($hasil);
    $cd=$data_L +1;
    $cd_L=$cd; 
    $cntr=$cd_L;



    $input=mysqli_query($conn,"INSERT INTO table_no_antrian(no_antrian,huruf,jenis,st,tgl,waktu,cntr) VALUES ('$result_code_L','$huruf_L','$jenis','$status','$tgl','$waktu','$cntr')");
    if($input){
    print"<script>no_antrian=$result_code_L&huruf=$huruf_L&jenis=$jenis&st=$status&tgl=$tgl'</script> ";
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
        <p id="text"><img src="../dist/img/logo-mcare.png"/><u>MAJU CARE SERVICE CENTER</u><br>Jl.Pahlawan No. 38-40, Kota Madiun <br><?php print"Loket : $jenis"?><br><p id="kode"><?php print"$huruf_L $result_code_L"?></p></p>
        <p ><?php print $tgl?><br>TERIMA KASIH <br> ATAS KUNJUNGAN ANDA</p>
        <p> </p>
    </div>

<script type="text/javascript">
    // window.print();
    window.onload=function(){self.print();}
    window.onfocus=function(){window.close();}
    window.onmousemove=function(){window.close();}
</script>
</body>