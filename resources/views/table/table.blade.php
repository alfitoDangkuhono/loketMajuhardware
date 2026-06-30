@include('headadmin.headadmin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script>
    $(function () {
        function refreshTable() {
            if (document.hidden) {
                setTimeout(refreshTable, 5000);
                return;
            }
            $('#mangofresh').load('{{ $mangoUrl }}', function () {
                setTimeout(refreshTable, 5000);
            });
        }
        setTimeout(refreshTable, 5000);
    });
</script>

<div id="mangofresh"></div>

@include('headadmin.footeradmin')
