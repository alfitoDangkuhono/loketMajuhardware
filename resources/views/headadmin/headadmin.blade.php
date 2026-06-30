<head>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href={{asset("https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback")}}>
  <!-- Font Awesome -->
  <link rel="stylesheet" href={{asset("plugins/fontawesome-free/css/all.min.css")}}>
  <!-- Ionicons -->
  <link rel="stylesheet" href={{asset("https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css")}}>
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href={{asset("plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css")}}>
  <!-- iCheck -->
  <link rel="stylesheet" href={{asset("plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}>
  <!-- JQVMap -->
  <link rel="stylesheet" href={{asset("plugins/jqvmap/jqvmap.min.css")}}>
  <!-- Theme style -->
  <link rel="stylesheet" href={{asset("dist/css/adminlte.min.css")}}>
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href={{asset("plugins/overlayScrollbars/css/OverlayScrollbars.min.css")}}>
  <!-- Daterange picker -->
  <link rel="stylesheet" href={{asset("plugins/daterangepicker/daterangepicker.css")}}>
  <!-- summernote -->
  <link rel="stylesheet" href={{asset("plugins/summernote/summernote-bs4.min.css")}}>
</head>

<style>
  #jam{
    color: aqua;
    font-size: 30px;
  }
  #dash{
    background-color: #6f42c1;
    color: aliceblue
  }
  #name{
    color: cyan;
    text-transform: uppercase;
  }
  #regis{
    background-color: dodgerblue;
  }
  #logout{
    background-color: red;
    color: white;
  }

</style>

<?php
  date_default_timezone_set("Asia/jakarta");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
  <title>{{Auth::user()->name}}</title>
<body class="hold-transition sidebar-mini layout-fixed" >
<div class="wrapper">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img  class="brand-image img-circle elevation-3" src="{{URL::asset('dist/img/logo.jpeg')}}"  height="200" width="200">
  </div>
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
          <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
        </svg></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/home')}}" class="nav-link">Home</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-item"><li id="jam"><?php echo date('H : I : S')?></li></a>
        {{-- <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-angle-expand" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707zm4.344-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707z"/>
          </svg>
        </a> --}}
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{url('/home')}}" class="brand-link" >
      <img src="{{URL::asset('dist/img/logo.jpeg')}}"  class="brand-image img-circle elevation-3"  style="opacity: .8">
      <span class="brand-text font-weight-light">Maju Hardware</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-4 pb-4 mb-4 d-flex">
        <div class="image">
          <img src="{{URL::asset('/icon.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{url('/home')}}" id="name" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item menu-open">  
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/home')}}" id="dash" class="nav-link active">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                  </svg>
                  <p id="huruf">Dashboard </p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ route('register') }}" id="dash" class="nav-link active">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                  </svg>
                  <p id="huruf">Regis </p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route('logout') }}" id="logout" class="nav-link active">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                  </svg>
                  <p id="logout">Log Out </p>
                </a>
                {{-- @if (Route::has('password.request'))
                  <a class="btn btn-link" href="{{ route('password.request') }}">
                     {{ __('Forgot Your Password?') }}
                  </a>
                 @endif --}}
              </li>
            </ul>
          </li>     
        </ul>
      </nav>
    </div>
  </aside>
  


</body>
</html>

        {{-- SCRIPT JAM --}}
<script type="text/javascript">
  window.onload = function() { jam(); }
 
  function jam() {
      var e = document.getElementById('jam'),
      d = new Date(), h, m, s;
      h = d.getHours();                                               
      m = set(d.getMinutes());
      s = set(d.getSeconds());
 
      e.innerHTML = h +':'+ m +':'+ s;
 
      setTimeout('jam()', 1000);
      if(m>1){
        
      }
  }
  function set(e) {
      e = e < 10 ? '0'+ e : e;
      return e;
  }
</script>



