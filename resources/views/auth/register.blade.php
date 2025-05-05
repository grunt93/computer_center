@extends('layouts.app')

@section('title', '註冊')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-person-plus me-2"></i>註冊
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-person me-1"></i>姓名
                            </label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                           placeholder="請輸入您的姓名">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="student_id" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-card-heading me-1"></i>學號
                            </label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input id="student_id" type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                           name="student_id" value="{{ old('student_id') }}" required
                                           placeholder="請輸入您的學號">
                                    @error('student_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-text text-muted">
                                    <small>學號將自動轉為大寫</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-envelope me-1"></i>電子郵件
                            </label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required autocomplete="email"
                                           placeholder="請輸入您的電子郵件">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-lock me-1"></i>密碼
                            </label>
                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password" type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        name="password" required autocomplete="new-password"
                                        placeholder="請輸入密碼">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-shield-check me-1"></i>確認密碼
                            </label>
                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password-confirm" type="password" class="form-control" 
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="請再次輸入密碼">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password-confirm"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-person-plus me-1"></i>註冊
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6 offset-md-4">
                            <span class="me-2">已經有帳號？</span>
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="bi bi-box-arrow-in-right me-1"></i>登入
                            </a>
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
document.addEventListener('DOMContentLoaded', function() {
    // 學號自動轉大寫
    const studentIdInput = document.getElementById('student_id');
    if (studentIdInput) {
        studentIdInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // 表單提交前確保學號為大寫
    const registerForm = document.querySelector('form');
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            if (studentIdInput) {
                studentIdInput.value = studentIdInput.value.toUpperCase();
            }
        });
    }
});
</script>
@endpush