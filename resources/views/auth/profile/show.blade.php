@extends('layouts.app')

@section('title', '個人資料')

@push('styles')
<style>
    .profile-card {
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .profile-card:hover {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }
    .modal-header {
        border-bottom: 2px solid #f8f9fa;
    }
    .modal-footer {
        border-top: 2px solid #f8f9fa;
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* 響應式調整 */
    @media (max-width: 767.98px) {
        .profile-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .profile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            width: 100%;
            margin-top: 10px;
        }
        
        .profile-actions .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 個人資料卡片 -->
            <div class="card profile-card mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center profile-header">
                        <span class="mb-2 mb-md-0">
                            <i class="bi bi-person-circle me-2"></i>個人資料
                        </span>
                        <div class="btn-group profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-pencil-square me-1"></i>編輯資料
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">
                                <i class="bi bi-envelope me-1"></i>修改信箱
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                <i class="bi bi-lock me-1"></i>修改密碼
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i>刪除帳號
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">
                            <i class="bi bi-person me-2"></i>姓名
                        </label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">
                            <i class="bi bi-card-heading me-2"></i>學號
                        </label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->student_id }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">
                            <i class="bi bi-envelope me-2"></i>電子郵件
                        </label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 修改密碼 Modal -->
            <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="passwordModalLabel">
                                <i class="bi bi-lock me-2"></i>修改密碼
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">
                                        <i class="bi bi-key me-2"></i>目前密碼
                                    </label>
                                    <div class="input-password-wrapper">
                                        <input id="current_password" type="password" 
                                            class="form-control @error('current_password') is-invalid @enderror" 
                                            name="current_password" required>
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock me-2"></i>新密碼
                                    </label>
                                    <div class="input-password-wrapper">
                                        <input id="password" type="password" 
                                            class="form-control @error('password') is-invalid @enderror" 
                                            name="password" required>
                                        <i class="bi bi-eye password-toggle-icon" data-target="password"></i>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="bi bi-shield me-2"></i>確認新密碼
                                    </label>
                                    <div class="input-password-wrapper">
                                        <input id="password_confirmation" type="password" 
                                            class="form-control"
                                            name="password_confirmation" required>
                                        <i class="bi bi-eye password-toggle-icon" data-target="password_confirmation"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>取消
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>確認修改
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 修改信箱 Modal -->
            <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="emailModalLabel">
                                <i class="bi bi-envelope me-2"></i>修改電子郵件
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.email.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-at me-2"></i>新電子郵件
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                        <input id="email" type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            name="email" 
                                            value="{{ old('email', $user->email) }}" 
                                            required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email_password" class="form-label">
                                        <i class="bi bi-key me-2"></i>請輸入密碼確認
                                    </label>
                                    <div class="input-password-wrapper">
                                        <input id="email_password" type="password" 
                                            class="form-control @error('email_password') is-invalid @enderror" 
                                            name="email_password" required>
                                        @error('email_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>取消
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>確認修改
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 刪除帳號 Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteModalLabel">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>刪除帳號
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>警告：此操作無法復原！
                                </div>
                                <div class="mb-3">
                                    <label for="delete_confirmation" class="form-label">
                                        <i class="bi bi-key me-2"></i>請輸入密碼以確認
                                    </label>
                                    <div class="input-password-wrapper">
                                        <input id="delete_confirmation" type="password" 
                                            class="form-control @error('delete_confirmation') is-invalid @enderror" 
                                            name="delete_confirmation" required>
                                        @error('delete_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>取消
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>確認刪除
                                </button>
                            </div>
                        </form>
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
    // 檢查是否有修改密碼相關的錯誤
    @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        new bootstrap.Modal(document.getElementById('passwordModal')).show();
    @endif

    // 檢查是否有修改電子郵件相關的錯誤
    @if($errors->has('email') || $errors->has('email_password'))
        new bootstrap.Modal(document.getElementById('emailModal')).show();
    @endif

    // 檢查是否有刪除帳號相關的錯誤
    @if($errors->has('delete_confirmation'))
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    @endif
});
</script>
@endpush