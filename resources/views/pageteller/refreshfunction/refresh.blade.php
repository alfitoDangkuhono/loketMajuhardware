<div class="container-fluid">
    <div class="grid gap-0 row-gap-3">
        <div class="row">
            <div class="col-sm-3 col-md-6 bg-warning" id="main">
                <div id="title_main">
                    <center><h1 style="color:aliceblue">No mendatang</h1></center>
                </div>
                <br>
                <strong>
                    <center><h1 class="card-body" id="font">{{ $kode }}{{ sprintf('%02d', $noDtBelum) }}</h1></center>
                </strong>
            </div>
            <div class="col-sm-9 col-md-6 bg-success" id="main">
                <div id="title_main">
                    <center><h1 style="color:rgb(255, 247, 247)">No selesai</h1></center>
                </div>
                <br>
                <strong>
                    <center><h1 class="card-body" id="font">{{ $kode }}{{ sprintf('%02d', $noDtSudah) }}</h1></center>
                </strong>
            </div>
        </div>
    </div>
</div>
