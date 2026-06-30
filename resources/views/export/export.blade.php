<html>
<head>
    <title>{{ $title }} ({{ date('d/F/y') }})</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>
<body>
    <div class="container">
        <br><br><br>
        <div class="data-tables datatable-dark">
            <table id="mauexport" class="table table-bordered">
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
                    @foreach ($rows as $i => $data)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $data->huruf }} {{ sprintf('%02d', $data->no_antrian) }}</td>
                            <td>{{ $data->jenis }}</td>
                            <td>{{ $data->st }}</td>
                            <td>{{ $data->tgl ? date(' d F, Y ', strtotime($data->tgl)) : '' }}</td>
                            <td>{{ $data->waktu ? date('H : i', strtotime($data->waktu)) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(function () {
            $('#mauexport').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf', 'print']
            });
        });
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
