@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .password-toggle-icon {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }
    .input-password-wrapper {
        position: relative;
    }
    .confirm-card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .confirm-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card confirm-card">
                <div class="card-header bg-light">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-lock-fill me-2"></i>
                        <span>確認密碼</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        為了安全起見，請在繼續操作前確認您的密碼。
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-key me-2"></i>密碼
                            </label>

                            <div class="col-md-6">
                                <div class="input-password-wrapper">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <i class="bi bi-eye password-toggle-icon" data-target="password"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>確認密碼
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 密碼顯示/隱藏功能
    const toggleIcon = document.querySelector('.password-toggle-icon');
    if(toggleIcon) {
        toggleIcon.addEventListener('click', function() {
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
</script>
@endpush
