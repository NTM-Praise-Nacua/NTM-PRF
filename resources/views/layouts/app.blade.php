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
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        /* Transfer to custom.css ======= START */
        .btn-utility {
            font-size: 14px;
            font-family: 'Times New Roman', Times, serif;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            height: 30px;
            box-sizing: border-box;
        }
        /* Transfer to custom.css ======= END */

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
        /* .tab-link:not(.active-current):hover {
            opacity: .5;
            background: #005380;
        } */
        .active-current, .sidenav, .tab-link:not(.active-current):hover {
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
        <div class="sidebar position-sticky h-100 d-flex flex-column sidebar-close shadow">
            @include('layouts.include.sidebar')
        </div>
        @endauth
        <div class="d-flex flex-column flex-grow-1 position-relative overflow-y-auto">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm position-sticky top-0" style="z-index: 5;">
                <div class="d-flex align-items-center justify-content-between w-100 mx-2" style="height: 40px;">
    
                    <div class="d-flex justify-content-end w-100 position-relative" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
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
    
                                    <div class="dropdown-menu dropdown-menu-end shadow" style="position: absolute !important; top: 42px;" aria-labelledby="navbarDropdown">
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
            <footer class="footer border-top d-flex align-items-center justify-content-center ps-3">
                @include('layouts.include.footer')
            </footer>
            @endauth
        </div>
    </div>
    @stack('js')
    <script>
        function alertMessage(msg, status, location = "", confirm = false, id = null) {
            if (!confirm) {
                Swal.fire({
                    title: msg,
                    icon: status,
                    showConfirmButton: confirm,
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
            } else {
                Swal.fire({
                    title: msg,
                    icon: status,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteType(id);
                    }
                });
            }
        }

        function confirmMessage(id) {
            alertMessage("Are you sure?", 'warning', '', true, id);
        }

        function deleteType(id) {
            const token = $('meta[name="csrf-token"]').attr('content');
            const formData = new FormData();
            formData.append('type_id', id);
            formData.append('_token', token);

            $.ajax({
                url: '{{ route("type.delete") }}',
                type: "POST", 
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.status == 'success') {
                        alertMessage('Deleted', 'success')
                    }
                },
                error: function(xhr, error) {
                    alertMessage("Something went wrong!", "error");
                    console.error('error: ', xhr);
                    console.error('error: ', error);
                }
            });
        }

        function viewPDF(file, container, height = '1111px') {
            const src = file.dataset.src;

            container.empty();

            const frameEl = $('<iframe></iframe>')
                .css({
                    width: '100%',
                    height: height,
                    border: '1px solid gray'
                })
                .attr({
                    src: src
                });
            
            const frameWrapper = $('<div></div>', {
                class: 'frameWrapper position-relative border'
            });
            
            container.append(frameWrapper.append(frameEl));
        }

        function editPDF(container) {
            container.empty();
            const buttonWrapper = $('<div id="pdf-utilities"></div>')
                .css({
                    padding: '0.5rem',
                    background: '#262626',
                    gap: '5px',
                    display: 'flex',
                    position: 'sticky',
                    top: '0',
                });
            const saveBtn = $('<button></button>', {
                id: 'submit-edit',
                class: 'btn-utility',
                text: 'save',
                type: 'button',
            });
            const addTextBtn = $('<button></button>', {
                id: 'add-text',
                class: "btn-utility",
                text: 'T',
                type: 'button',
            });
            const decBtn = $('<button></button>', {
                id: 'decrease',
                class: "btn-utility",
                text: 'a',
                type: 'button',
            });
            const incBtn = $('<button></button>', {
                id: 'increase',
                class: "btn-utility",
                text: 'A',
                type: 'button',
            });
            buttonWrapper.append(saveBtn, addTextBtn, decBtn, incBtn);

            const pdfWrapper = $('<div></div>', {
                id: 'pdf-wrapper',
            }).css({
                border: '1px solid black',
                width: '100%',
                overflow: 'auto',
            });
            const pdfContainer = $('<div></div>', {
                id: 'pdf-container',
            }).css({
                position: 'relative',
                display: 'inline-block',
            });

            const pdfCanvas = $('<canvas id="pdf-canvas"></canvas>');

            container.append(buttonWrapper, pdfWrapper.append(pdfContainer.append(pdfCanvas)));
        }

        $('.sidenav .navbar-brand').on('click', function() {
            window.location.reload();
        });
    </script>
</body>
</html>
