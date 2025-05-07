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

                        <!-- 顯示電子郵件 (唯讀) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">電子郵件</label>
                            <input id="email" type="email" class="form-control" value="{{ $email }}" readonly>
                        </div>

                        <!-- 密碼欄位 -->
                        <div class="mb-3">
                            <label for="password" class="form-label">新密碼</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- 確認密碼欄位 -->
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">確認密碼</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <!-- 提交按鈕 -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">設置密碼並登入</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection