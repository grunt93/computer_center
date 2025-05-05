@extends('layouts.app')

@section('title', '發送重設連結')

@push('styles')
<style>
    .reset-card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .reset-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush

@section('content')
<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card reset-card">
                <div class="card-header bg-light">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-key-fill me-2"></i>
                        <span>重設密碼</span>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        請輸入您的電子郵件地址，我們將發送重設密碼連結給您。
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">
                                <i class="bi bi-envelope me-2"></i>電子郵件
                            </label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="請輸入您的電子郵件">
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send-fill me-2"></i>發送重設連結
                                </button>
                                <a class="btn btn-link mt-2 text-center" href="{{ route('login') }}">
                                    <i class="bi bi-arrow-left-circle me-1"></i>返回登入頁面
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
