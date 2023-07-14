@include('headadmin.headadmin')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Table No Antrian CPU</h1>
        </div>
      </div>
    </div>
  </section>
   <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <a href="{{Route('convert_C')}}"><span>Export</span></a>
            </div>
              <div class="card-body">
                  <table id="mauexport" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>NO</th>
                        <th>NO Antrian</th>
                        <th>Jenis Antrian</th>
                        <th>Status</th>
                        <th>Tanggal / Bulan / Tahun</th>
                        <th>Waktu</th>
                      </tr>
                    </thead>
                      <?php
                        $conn=mysqli_connect("localhost","root","","antrian_mh");                 
                        $query=mysqli_query($conn,"SELECT * FROM table_no_antrian WHERE jenis='CPU'") ;
                      if (mysqli_num_rows($query)>0) {?>
                      <?php
                      $no=1;
                      while ($data=mysqli_fetch_array($query)) {
                      ?>
                    <tbody>
                      <tr>
                        <td><?php echo $no++ ?>.</td>
                        <td> <?php echo $data["huruf"]," ",sprintf("%02d",$data["no_antrian"])?></td>
                        <td><?php echo $data["jenis"]?></td>
                        <td><?php echo $data["st"]?></td>
                        <td><?php echo date(' d F, Y ',strtotime($data["tgl"])); ?></td>
                        <td><?php echo date('H : i',strtotime($data["waktu"]));?></td>   
                      </tr>
                      <?php }?>
                      <?php }?>
                    </tbody>
                  </table>
              </div>
          </div>
    </div>
  </section>
   
{{-- <script type="text/javascript">  
  setTimeout(function(){  
      location.reload();  
  },5000);  
</script>  --}}

  {{-- @extends('functiontable.functiontable') --}}
  @include('headadmin.footeradmin')

 	






  

