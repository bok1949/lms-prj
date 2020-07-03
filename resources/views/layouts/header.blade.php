{{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm custom-header"> --}}
<nav class="navbar fixed-top navbar-expand-md navbar-light shadow-sm custom-header">
    <div class="container">
        <a class="navbar-brand d-flex" href="#">
            <div><img src="/logo/80x80.png" style="height:55px; border-right: 1px solid #4c4c4c;" class="pr-3"></div>
            <div class="pl-3 pt-2 head-text">King's College of the Philippines</div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            @if (isset($useraccount))
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
                            <i class="fa fa-user" aria-hidden="true"></i> 
                            @foreach ($useraccount as $ua)
                                @php
                                    $lastname = $ua->last_name;
                                    $id = $ua->id;
                                    $utype = $ua->user_type;
                                @endphp
                            @endforeach
                            {{$lastname}}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route($utype.'accountsettings')}}"> Change Password</a>
                            <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>