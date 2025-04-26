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
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 個人資料卡片 -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>個人資料</span>
                        <div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-info btn-sm me-2">
                                編輯資料
                            </a>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#emailModal">
                                修改信箱
                            </button>
                            <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                修改密碼
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                刪除帳號
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">姓名</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">學號</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->student_id }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">電子郵件</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 修改密碼 Modal -->
            <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="passwordModalLabel">修改密碼</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">目前密碼</label>
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
                                    <label for="password" class="form-label">新密碼</label>
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
                                    <label for="password_confirmation" class="form-label">確認新密碼</label>
                                    <input id="password_confirmation" type="password" 
                                        class="form-control"
                                        name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">確認修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 修改信箱 Modal -->
            <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="emailModalLabel">修改電子郵件</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.email.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="email" class="form-label">新電子郵件</label>
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
                                <div class="mb-3">
                                    <label for="email_password" class="form-label">請輸入密碼確認</label>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">確認修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 刪除帳號 Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="deleteModalLabel">刪除帳號</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('profile.delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p class="text-danger">警告：此操作無法復原！</p>
                                <div class="mb-3">
                                    <label for="delete_confirmation" class="form-label">請輸入密碼以確認</label>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-danger">確認刪除</button>
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
$(document).ready(function() {
    // 密碼顯示/隱藏功能
    $('.password-toggle-icon').click(function() {
        const targetId = $(this).data('target');
        const passwordInput = $(`#${targetId}`);
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            $(this).removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            $(this).removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    // 檢查是否有修改密碼相關的錯誤
    @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        $('#passwordModal').modal('show');
    @endif

    // 檢查是否有修改電子郵件相關的錯誤
    @if($errors->has('email') || $errors->has('email_password'))
        $('#emailModal').modal('show');
    @endif

    // 檢查是否有刪除帳號相關的錯誤
    @if($errors->has('delete_confirmation'))
        $('#deleteModal').modal('show');
    @endif
});
</script>
@endpush