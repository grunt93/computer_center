@extends('layouts.app')

@section('title', '登入')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>登入
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-envelope me-1"></i>學號
                            </label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required autofocus 
                                           placeholder="請輸入電子郵件或學號">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted mt-1">
                                    學號開頭英文大小寫都OK
                                </small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-lock me-1"></i>密碼
                            </label>
                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" autocomplete="current-password" 
                                           placeholder="請輸入密碼">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password" title="顯示/隱藏密碼"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted mt-1">
                                    第一次登入，可無須輸入密碼
                                </small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        <i class="bi bi-clock-history me-1"></i>記住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>登入
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        <i class="bi bi-question-circle me-1"></i>忘記密碼？
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6 offset-md-4">
                            @if (Route::has('register'))
                                <span class="me-2">還沒有帳號？</span>
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    <i class="bi bi-person-plus me-1"></i>註冊
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // 獲取密碼欄位
        const passwordField = $('#password');
        
        // 美化提示訊息
        $('.form-text:contains("第一次登入")').addClass('first-login-hint');
        
        // 當用戶點擊提示訊息時，自動聚焦到提交按鈕
        $('.first-login-hint').on('click', function() {
            $('button[type="submit"]').focus();
        });
    });
</script>
@endpush