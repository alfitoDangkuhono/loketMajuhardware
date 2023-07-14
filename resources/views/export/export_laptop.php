
<html>
<head>
  <title>LAPTOP (<?php echo  date("d/F/y")?>) </title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
		<br>
        <br><br>
				<div class="data-tables datatable-dark">
					
                        <!-- Masukkan table nya disini, dimulai dari tag TABLE -->
                        <table id="mauexport" class="table table-bordered ">
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
                        <tbody>
                            <?php
                            require'conn.php';
                        $query=mysqli_query($koneksi,"SELECT * FROM table_no_antrian WHERE jenis='Laptop'") ;
                        // $query_2=mysqli_query($koneksi,"SELECT * FROM table_no_antrian WHERE jenis='Gadget'") ;
                        $no=1;
                        while ($data=mysqli_fetch_array($query)) {
                                $huruf=$data['huruf'];
                                $no_antrian=$data['no_antrian'];
                                $jenis=$data['jenis'];
                                $st=$data['st'];
                                $tgl=$data['tgl'];
                                $waktu=$data['waktu'];
                        
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?php echo $huruf," ",$no_antrian?></td>
                                <td><?php echo $jenis?></td>
                                <td><?php echo $st ?></td>
                                <td><?php echo date(' d F, Y ',strtotime($data["tgl"]))?></td>
                                <td><?php echo date('H : i',strtotime($data["waktu"])) ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
					
				</div>
                
</div>
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>