<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        .sidebar {
            width: 250px;
            min-width: 250px;
            background: #024a70;
        }
        #usersDropdown {
            background: #033D5E;
        }
        #usersDropdown .nav-item {
            border-radius: 5px;
        }
        #usersDropdown .nav-item:hover {
            background: #024a70;
        }
        .footer {
            height: 50px;
            min-height: 50px;
            background: #f3f4f6;
        }
        .circle {
            height: 45px;
            width: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .tab-link {
            color: white;
        }
        .tab-link:not(.active-current):hover {
            opacity: 0.75;
            background: #025178;
        }
        .active-current, .sidenav {
            background: #00598a !important;
        }
        .active-current:hover, {
            color: lightgray !important;
        }
        table th {
            background: #212529 !important;
            color: white !important;
        }
        .top-left {
            float: left;
        }
    </style>

    @stack('css')
</head>
<body>
    <div id="app" class="d-flex container-fluid p-0 vh-100">
        @auth
        <div class="sidebar position-sticky h-100 d-flex flex-column">
            @include('layouts.include.sidebar')
        </div>
        @endauth
        <div class="d-flex flex-column flex-grow-1 position-relative overflow-y-auto">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm position-sticky top-0" style="z-index: 5;">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
    
                        </ul>
    
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
    
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
    
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
    
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
    
            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>

            @auth
            <footer class="footer border-top d-flex align-items-center ps-3">
                @include('layouts.include.footer')
            </footer>
            @endauth
        </div>
    </div>
    @stack('js')
    <script>
        function alertMessage(msg, status, location = "") {
            Swal.fire({
                title: msg,
                icon: status,
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer && status == "success") {
                    if (!location) {
                        window.location.reload();
                    } else {
                        window.location.href = location;
                    }
                }
            });
        }

        function viewPDF(file, container, height = '1111px') {
            const src = file.dataset.src;

            container.empty();
            console.log('container: ', container);

            const frameEl = $('<iframe></iframe>')
                .css({
                    width: '100%',
                    height: height,
                })
                .attr('src', src);
            
            container.append(frameEl);
        }
    </script>
</body>
</html>
