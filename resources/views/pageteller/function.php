<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
</head>
<body>
    
<?php
    $jumlah=strlen($no);
    if($jumlah==1){
        $no=$no;
    }
    else if($jumlah==2){
        $nomorA=substr($no,0,1);                    //UNTUK MENGAMBIL DAN MEMISAHKAN VALUE MISAL 23 menjadi 2-3
        $nomorB=substr($no,1,1);
    }
?>

<div id="audioplay">
    
    <audio id='audio1' src='audio/awal.ogg' preloader='auto' type='audio/ogg'></audio>
    <audio id='audio2' src='audio/nomor-urut.ogg' preloader='auto' type='audio/ogg'></audio>
    <audio id='audio3' src='audio/<?php print"$_SESSION[kode].ogg";?>' preloader='auto' type='audio/ogg'></audio>
    
    <?php
        if ($jumlah==1) {
            print"<audio id='audio5' src='audio/0.ogg' preloader='auto' type='audio/ogg'></audio>";
            print"<audio id='audio4' src='audio/$no.ogg' preloader='auto' type='audio/ogg'></audio>";
        }
        else if ($no==10) {
            print"<audio id='audio4' src='audio/10.ogg' preloader='auto' type='audio/ogg'></audio>";
        }
        else if ($no==11) {
            print"<audio id='audio4' src='audio/11.ogg' preloader='auto' type='audio/ogg'></audio>";
        }
        else if ($jumlah==2 and $no <  20) {
            print"<audio id='audio4' src='audio/$nomorB.ogg' preloader='auto' type='audio/ogg'></audio>";
            print"<audio id='audio6' src='audio/belas.ogg' preloader='auto' type='audio/ogg'></audio>";
        }
        else if ($jumlah==2 and $no >  19) {
            print"<audio id='audio4' src='audio/$nomorA.ogg' preloader='auto' type='audio/ogg'></audio>";       
            print"<audio id='audio6' src='audio/puluh.ogg' preloader='auto' type='audio/ogg'></audio>";
            print"<audio id='audio7' src='audio/$nomorB.ogg' preloader='auto' type='audio/ogg'></audio>";
        }
        else if($no==100){
            print"<audio id='audio4' src='audio/100.ogg'></audio>";
        }
    ?>
    <audio id='audio8' src='audio/loket.ogg' preloader='auto' type='audio/ogg'></audio>
    <audio id='audio9' src='audio/<?php print"$_SESSION[jenis].ogg";?>' preloader='auto' type='audio/ogg'></audio>
    <audio id='audio10' src='audio/akhir.ogg' preloader='auto' type='audio/ogg'></audio>

</div>

<script type="text/javascript"  href="js/jquery-1.11.3.min.js" rel="javascript">
    
    function panggil(){

        var audio1=document.getElementById('audio1');           //BELL
        var audio2=document.getElementById('audio2');           //NOMOR URUT
        var audio3=document.getElementById('audio3');           //HURUF
        var audio4=document.getElementById('audio4');           //NO
        var audio5=document.getElementById('audio5'); 
        var audio6=document.getElementById('audio6');           //PULUH/BELAS
        var audio7=document.getElementById('audio7');           //NO DAN VARIABLE PENENTU A DAN B 
        var audio8=document.getElementById('audio8');           //LOKET
        var audio9=document.getElementById('audio9');           //NAMA LOKET
        var audio10=document.getElementById('audio10'); 

        audio1.play();
        setTimeout(function()  {audio2.play();}, 3000);   
        setTimeout(function()  {audio3.play();}, 4500);   
        setTimeout(function()  {audio5.play();}, 5200);   
        setTimeout(function()  {audio4.play();}, 6000);
        setTimeout(function()  {audio6.play();}, 7000);         
        setTimeout(function()  {audio7.play();}, 8000);
        setTimeout(function()  {audio8.play();}, 8700);        
        setTimeout(function()  {audio9.play();}, 9500);
        setTimeout(function()  {audio10.play();}, 11000);

        <?php       
            $conn=mysqli_connect("localhost","root","","antrian_mh");
            $value="sudah";
            $query=mysqli_query($conn,"UPDATE table_no_antrian SET st='$value' WHERE  no_antrian=$no AND jenis='$_SESSION[jenis]'");
         ?> 
    }  

</script> 
     
<script type="text/javascript">
    function next_no(){
        window.location='<?php print" $_SESSION[halaman]"?>';   
    }
</script>
 

</body>
</html>







            <!-- CODE GAGAL -->
<?php
            // $conn=mysqli_connect("localhost","root","","antrian_mh");
            // $value="sudah";
            // $query=mysqli_query($conn,"UPDATE table_no_antrian SET st='$value' WHERE jenis='Laptop'  AND no_antrian=$no ");
?>
  
<!-- function ajaxFunction(){
$.ajax({
          type: "POST",
          url: "doStuff.php",
          data: {key: value}, //data to send as key-value pairs
          context: this,
          success: function(data){
            //do stuff when done running
          }
        });

        
        // let jenis = $('<?php// print"$_SESSION[jenis]";?>').val();
            //halaman=$('uknown_11').val(),
          //  no = $('<?php// print"$no";?>').val();
            //token=$('<?php //print" {{csrf_token()}}"?>').val();
            
          
            

        // $.ajax({
            
        //     method: "Post",
        //     url:  'uknown_11',
        //     data: {
        //         //token:token,
        //         //jenis:jenis,
        //         //no:no
        //            // jenis:<?php //echo"$_SESSION[jenis]";?>,
        //             no_antrian:<?php //print"$no"?>
        //             // jenis:jenis,no_antrian:<?php// print"$no"?>;
        //     }
        //     // contentType: "application/json; charset=utf-8",
        //     //  dataType:"json",
        //     //  cache:false

        // });
} -->