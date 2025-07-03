<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/custom/js/mode.js') }}"></script>
    <script src="{{ asset('assets/custom/js/section.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/custom/js/swal2.min.js') }}"></script>
    <link href="{{ asset('assets/custom/css/global.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/custom/img/favicon.ico') }}">
    <meta name="theme-color" content="#712cf9">

    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
        }

        .navbar .nav-link {
            display: flex;
            align-items: center;
        }

        .navbar-nav>.nav-item {
            margin-right: 1rem;
        }

        .navbar .form-switch {
            display: flex;
            align-items: center;
            margin-left: 1rem;
        }

        .navbar .form-switch input {
            margin-right: 0.4rem;
        }

        .navbar .nav-link i {
            margin-right: 6px;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }

        .navbar .ms-auto {
            margin-right: 0.25rem;
        }

        [data-bs-theme="light"] .navbar {
            background-color: #f8f9fa;
        }

        [data-bs-theme="light"] .nav-link {
            color: #000;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #292a2c;
        }

        [data-bs-theme="dark"] .nav-link {
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: var(--bs-body-bg);
                z-index: 1000;
                padding: 1rem;
            }

            .navbar-collapse .navbar-nav {
                align-items: flex-start !important;
            }

            .navbar-collapse .form-switch {
                justify-content: flex-start !important;
            }

            .navbar .ms-auto {
                margin-top: 1rem;
                justify-content: flex-start !important;
            }

            body.offcanvas-open {
                overflow: hidden;
            }
        }
    </style>
</head>

<body class="preload">
    <nav class="navbar navbar-expand-lg border-body sticky-top">
        <div class="container-fluid px-3 px-lg-5">
            <a class="navbar-brand fw-bold" href="/"><img src="{{ asset('assets/custom/img/logo.png') }}" width="28px" alt="Logo"> Gallery App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    @if (auth()->check())
                        <li class="nav-item {{ request()->is('album*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('album') }}"><i class="fa-solid fa-images"></i> My Albums</a>
                        </li>
                        <li class="nav-item {{ request()->is('photo*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('foto.index') }}"><i class="fa-solid fa-image"></i> My
                                Photos</a>
                        </li>
                    @endif
                    <li class="nav-item form-switch text-light">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        <label class="form-check-label" for="darkModeSwitch">
                            <i class="fa-solid fa-sun" style="color: #272727" id="lightModeIcon"></i>
                            <i class="fa-solid fa-moon" id="darkModeIcon"></i>
                        </label>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
                    @if (auth()->check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->profile_picture ? asset('storage/user_profile/' . Auth::user()->profile_picture) : asset('assets/default-avatar.jpg') }}"
                                    alt="Profile Picture" class="rounded-circle"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                <span class="ms-2">{{ Auth::user()->Username }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i
                                            class="fa-solid fa-user"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                            class="fa-solid fa-right-from-bracket"></i> Log Out</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" aria-current="page" href="{{ route('register.create') }}">
                                <i class="fa-solid fa-user-plus"></i>&nbsp;Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" aria-current="page" href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket"></i>&nbsp;Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
    @include('layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggler = document.querySelector('.navbar-toggler');
            const collapse = document.getElementById('navbarSupportedContent');

            toggler.addEventListener('click', function () {
                setTimeout(() => {
                    document.body.classList.toggle('offcanvas-open', collapse.classList.contains('show'));
                }, 100);
            });
        });
    </script>
</body>

</html>