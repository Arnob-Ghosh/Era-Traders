<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Era Traders</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    {{-- <link rel="icon"href="{{asset('')}}assets/img/kaiadmin/favicon.ico"type="image/x-icon"/> --}}

    <!-- Fonts and icons -->
    <script src="{{ asset('') }}assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/demo.css" />
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- DataTables Bootstrap 5 Integration CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">

<style>
    .dataTables_wrapper .dt-buttons {
    float: right;
}

</style>
</head>

<body>
    <div class="wrapper">
      @include('layouts.sidebar')


        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="/dashboard" class="logo">
                            {{-- <img src="{{asset('')}}assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"  height="20" /> --}}
                            <h4> ERA TRADERS</h4>
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
              
            @include('layouts.top-navbar')

            </div>

            <div class="container">
                @yield('content')
            </div>
            @include('layouts.footer')

         
        </div>
        @include('layouts.custom-template')
        <!-- Custom template | don't include it in your project! -->
      
        <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('') }}assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('') }}assets/js/core/popper.min.js"></script>
    <script src="{{ asset('') }}assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('') }}assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    

    <!-- Chart JS -->
    <script src="{{ asset('') }}assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('') }}assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('') }}assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('') }}assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('') }}assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('') }}assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ asset('') }}assets/js/plugin/jsvectormap/world.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('') }}assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('') }}assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset('') }}assets/js/setting-demo.js"></script>
    
 @yield('js')

</body>

</html>
