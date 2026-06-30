<style>
    .card-header { background-color: rgb(26, 186, 226); }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Table No Antrian {{ $title }}</h1>
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
                            <a href="{{ $exportUrl }}">
                                <button type="button" class="btn btn-success">Export</button>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Kode Antrian</th>
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
                </div>
            </div>
        </div>
    </section>
</div>
