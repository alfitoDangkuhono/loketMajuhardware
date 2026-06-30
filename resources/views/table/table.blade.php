@include('headadmin.headadmin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script>
    $(function () {
        setInterval(function () {
            $('#mangofresh').load('{{ $mangoUrl }}');
        }, 1000);
    });
</script>

<div id="mangofresh"></div>

@include('headadmin.footeradmin')
