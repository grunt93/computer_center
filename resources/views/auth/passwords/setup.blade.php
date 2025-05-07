@extends('layouts.app')

@section('title', '設置密碼')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-shield-lock me-2"></i>首次登入設置密碼
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>這是您的第一次登入，請先設置密碼以繼續使用系統。
                    </div>

                    <form method="POST" action="{{ route('password.setup.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>您的電子郵件
                            </label>
                            <input id="email" type="email" class="form-control" value="{{ $email }}" readonly>
                            <div class="form-text">由管理員建立的帳號，請設置您的密碼。</div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>新密碼
                            </label>
                            <div class="input-password-wrapper">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autofocus>
                                <i class="bi bi-eye password-toggle-icon" data-target="password"></i>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">密碼至少需要 8 個字元。</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class="bi bi-shield me-1"></i>確認密碼
                            </label>
                            <div class="input-password-wrapper">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                                <i class="bi bi-eye password-toggle-icon" data-target="password-confirm"></i>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>設置密碼並登入
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection