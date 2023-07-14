
<script type="text/javascript">
<?php
   //   $_SESSION['halaman_1']="uknown_11";
   $no_update=$_POST['no'];
   //$lk=$_POST['jenis'];
   $value="sudah";
    $conn=mysqli_connect("localhost","root","","antrian_mh");
    $query=mysqli_query($conn,"UPDATE table_no_antrian SET st='$value' WHERE no_antrian=$no_update And jenis='$lk'  ");
 ?>
 </script>



       
        
    


            