<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    {{-- SweetAlert --}}
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customStyle.css') }}" rel="stylesheet">
</head>
<body>
    @include('sweetalert::alert')
    {{-- <header class="mb-2"> --}}
        @yield('header')
   {{--  </header> --}}
    <br>
    <br>
    <br>
    
    {{-- <div class="container-fluid pt-4" id="app"> --}}
       
            
            <main class="container-fluid mt-2" id="app">
                @yield('content')
            </main>
            {{-- <div class="container-fluid mt-2">
                @yield('content')
            </div> --}}
            

        
    {{-- </div> --}}
    {{-- <footer class="pt-5" style="background-color: brown;"> --}}
                @yield('footer')
            {{-- </footer> --}}
    @yield('javascripts')
   
    
    <script>
        $(document).ready(function(){
            //alert('Workign');
            $('[data-toggle="tooltip"]').tooltip();
           /*  $("#myModalEdit").modal('show'); */
        });

    </script>
</body>
</html>
