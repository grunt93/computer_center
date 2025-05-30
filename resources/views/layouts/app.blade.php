<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="shortcut icon" href="{{ asset('image/Logo.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('image/Logo.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            background-color: #ffffff;
        }

        .navbar-brand {
            font-weight: 600;
            color: #0d6efd;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .btn {
            border-radius: 0.25rem;
            font-weight: 500;
            padding: 0.375rem 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .form-control {
            border-radius: 0.375rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            color: #0d6efd;
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .input-password-wrapper {
            position: relative;
        }

        .password-toggle-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #0d6efd;
            border: 1px solid #dee2e6;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* 漢堡按鈕動畫 */
        .navbar-toggler {
            border: none;
            padding: 0;
            width: 30px;
            height: 30px;
            position: relative;
            transition: all 0.3s;
            outline: none !important;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: none !important;
            position: relative;
            transition: all 0.3s;
            display: block;
            height: 2px;
            width: 100%;
            background-color: #0d6efd;
            margin: 5px 0;
        }

        .navbar-toggler-icon::before,
        .navbar-toggler-icon::after {
            content: '';
            position: absolute;
            left: 0;
            height: 2px;
            width: 100%;
            background-color: #0d6efd;
            transition: all 0.3s ease;
        }

        .navbar-toggler-icon::before {
            top: -8px;
        }

        .navbar-toggler-icon::after {
            top: 8px;
        }

        /* 展開時的動畫 */
        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
            background-color: transparent;
        }

        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::before {
            transform: translateY(8px) rotate(45deg);
        }

        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::after {
            transform: translateY(-8px) rotate(-45deg);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('image/Logo.png') }}" alt="學校標誌" height="30" class="me-2">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> 儀錶板</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('classroom.status') }}"><i
                                        class="bi bi-display me-1"></i> 教室狀態</a>
                            </li>
                            @auth
                                @if(Auth::user()->role === 'admin' || Auth::user()->role == 'super_admin')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('classroom.refresh.form') }}"><i
                                                class="bi bi-arrow-clockwise me-1"></i> 課表更新</a>
                                    </li>
                                @endif
                            @endauth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('disk-replacement.index') }}"><i
                                        class="bi bi-hdd me-1"></i> 硬碟更換記錄</a>
                            </li>
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('classroom.open') }}"><i
                                    class="bi bi-door-open me-1"></i>教室開門</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('https://cnc.uch.edu.tw/p/404-1002-577.php?Lang=zh-tw') }}"
                                target="_blank" rel="noopener noreferrer"><i class="bi bi-telephone me-1"></i> 分機聯絡表</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i
                                            class="bi bi-box-arrow-in-right me-1"></i> 登入</a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn"
                                    aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="bi bi-person me-2"></i> 個人資料
                                        </a>
                                    @if(Auth::user()->role === 'super_admin')
                                        <a class="dropdown-item" href="{{ route('profile.users.index') }}">
                                            <i class="bi bi-people me-2"></i> 用戶管理
                                        </a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> 登出
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

        <main class="py-4">
            <div class="container mb-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            function initPasswordToggleIcons() {
                $('.password-toggle-icon').each(function () {
                    if (!$(this).attr('data-initialized')) {
                        $(this).attr('data-initialized', 'true');
                        $(this).on('click', function () {
                            var targetId = $(this).attr('data-target');
                            var passwordInput = $('#' + targetId);

                            if (passwordInput.attr('type') === 'password') {
                                passwordInput.attr('type', 'text');
                                $(this).removeClass('bi-eye').addClass('bi-eye-slash');
                            } else {
                                passwordInput.attr('type', 'password');
                                $(this).removeClass('bi-eye-slash').addClass('bi-eye');
                            }
                        });
                    }
                });
            }

            initPasswordToggleIcons();

            $(document).on('shown.bs.modal', function () {
                setTimeout(function () {
                    initPasswordToggleIcons();
                }, 100);
            });

            $('input[id="student_id"]').on('input', function () {
                $(this).val($(this).val().toUpperCase());
            });

            $('form').on('submit', function () {
                var studentIdInput = $(this).find('#student_id');
                if (studentIdInput.length) {
                    studentIdInput.val(studentIdInput.val().toUpperCase());
                }
            });

            // 漢堡按鈕動畫效果
            $('.navbar-toggler').on('click', function() {
                // 按鈕切換動畫是透過 aria-expanded 屬性來控制
                // Bootstrap 會自動切換這個屬性
                $(this).toggleClass('is-active');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>