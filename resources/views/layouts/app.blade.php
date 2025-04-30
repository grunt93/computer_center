<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- 引入 Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 引入 Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- 引入 Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- 自定義 CSS -->
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

        /* 動畫效果 */
        .fade-in {
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        /* 表單元素 */
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
        
        /* 標籤樣式 */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        
        /* 分頁樣式 */
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
    </style>

    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-pc-display-horizontal me-2"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- 左邊選單 -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> 首頁</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('classroom.status') }}"><i class="bi bi-display me-1"></i> 教室狀態</a>
                        </li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('classroom.refresh.form') }}"><i class="bi bi-arrow-clockwise me-1"></i> 課表更新</a>
                                </li>
                            @endif
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('disk-replacement.index') }}"><i class="bi bi-hdd me-1"></i> 硬碟更換記錄</a>
                        </li>
                        @endauth
                    </ul>

                    <!-- 右邊選單 -->
                    <ul class="navbar-nav ms-auto">
                        <!-- 用戶相關 -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> 登入</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> 註冊</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->email !== 'admin')
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="bi bi-person me-2"></i> 個人資料
                                        </a>
                                    @endif
                                    @if(Auth::user()->role === 'admin')
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
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 密碼顯示/隱藏功能
        document.addEventListener('DOMContentLoaded', function() {
            // 為現有的密碼圖標綁定事件
            function initPasswordToggleIcons() {
                const passwordIcons = document.querySelectorAll('.password-toggle-icon');
                passwordIcons.forEach(icon => {
                    // 避免重複綁定事件
                    if (!icon.hasAttribute('data-initialized')) {
                        icon.setAttribute('data-initialized', 'true');
                        icon.addEventListener('click', function() {
                            const targetId = this.getAttribute('data-target');
                            const passwordInput = document.getElementById(targetId);
                            
                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                this.classList.remove('bi-eye');
                                this.classList.add('bi-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                this.classList.remove('bi-eye-slash');
                                this.classList.add('bi-eye');
                            }
                        });
                    }
                });
            }
            
            // 初始頁面加載時綁定事件
            initPasswordToggleIcons();
            
            // 監聽模態框顯示事件，在模態框顯示時重新綁定事件
            document.addEventListener('shown.bs.modal', function() {
                setTimeout(function() {
                    initPasswordToggleIcons();
                }, 100);
            });
            
            // 自動將學號轉為大寫
            const studentIdInputs = document.querySelectorAll('input[id="student_id"]');
            studentIdInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });
            
            // 表單提交時確保學號為大寫
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const studentIdInput = this.querySelector('#student_id');
                    if (studentIdInput) {
                        studentIdInput.value = studentIdInput.value.toUpperCase();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>