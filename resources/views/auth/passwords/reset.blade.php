@extends('layouts.app')

@section('title', '重設密碼')

@push('styles')
<style>
    .reset-card {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-radius: 8px;
        overflow: hidden;
        border: none;
        transition: all 0.3s;
    }
    .reset-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .reset-card .card-header {
        background: linear-gradient(45deg, #4a6bff, #2948ff);
        color: white;
        font-weight: 500;
        padding: 15px 20px;
        border: none;
    }
    .reset-card .card-body {
        padding: 30px;
    }
    .reset-btn {
        background: linear-gradient(45deg, #4a6bff, #2948ff);
        border: none;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(41, 72, 255, 0.3);
    }
    .animate-fade {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container animate-fade">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card reset-card">
                <div class="card-header">
                    <i class="bi bi-key-fill me-2"></i>重設密碼
                </div>

                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        請輸入您的新密碼。密碼至少需要8個字元，並建議包含數字和特殊字元。
                    </div>
                    
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-envelope-fill me-2"></i>電子郵件
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value="{{ $email ?? old('email') }}" 
                                    required 
                                    autocomplete="email" 
                                    readonly 
                                    style="background-color: #f8f9fa; border-color: #dee2e6;">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-shield-lock-fill me-2"></i>新密碼
                            </label>
                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2" id="password-strength"></div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-check-circle-fill me-2"></i>確認新密碼
                            </label>
                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password-confirm"></i>
                                </div>
                                <div class="password-match mt-2" id="password-match"></div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary reset-btn">
                                    <i class="bi bi-check2-all me-2"></i>重設密碼
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 移除密碼顯示/隱藏事件處理，使用 app.blade.php 中的全域實現
    
    // 密碼強度檢查
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    const confirmInput = document.getElementById('password-confirm');
    const matchIndicator = document.getElementById('password-match');
    
    function checkPasswordStrength() {
        const password = passwordInput.value;
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
        
        switch(strength) {
            case 0:
                strengthIndicator.textContent = '';
                break;
            case 1:
                strengthIndicator.textContent = '密碼強度: 非常弱';
                strengthIndicator.style.color = '#dc3545';
                break;
            case 2:
                strengthIndicator.textContent = '密碼強度: 弱';
                strengthIndicator.style.color = '#fd7e14';
                break;
            case 3:
                strengthIndicator.textContent = '密碼強度: 中等';
                strengthIndicator.style.color = '#ffc107';
                break;
            case 4:
                strengthIndicator.textContent = '密碼強度: 強';
                strengthIndicator.style.color = '#20c997';
                break;
            case 5:
                strengthIndicator.textContent = '密碼強度: 非常強';
                strengthIndicator.style.color = '#198754';
                break;
        }
    }
    
    function checkPasswordMatch() {
        if (confirmInput.value === '') {
            matchIndicator.textContent = '';
            return;
        }
        
        if (passwordInput.value === confirmInput.value) {
            matchIndicator.textContent = '密碼匹配';
            matchIndicator.style.color = '#198754';
        } else {
            matchIndicator.textContent = '密碼不匹配';
            matchIndicator.style.color = '#dc3545';
        }
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordStrength);
        passwordInput.addEventListener('input', checkPasswordMatch);
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
});
</script>
@endpush