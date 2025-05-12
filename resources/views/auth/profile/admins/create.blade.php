@extends('layouts.app')

@section('title', '新增用戶')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>新增用戶</h5>
                    <a href="{{ route('profile.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>返回用戶列表
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.users.store') }}" id="createUserForm">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">姓名</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">學號</label>
                            <input id="student_id" type="text" class="form-control @error('student_id') is-invalid @enderror" name="student_id" value="{{ old('student_id') }}" required>
                            @error('student_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">角色</label>
                            <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>職員</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>管理員</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>超級管理員</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>用戶將在首次登入時設置密碼。預設電子郵件將設為：學號@gapps.uch.edu.tw
                            <input type="hidden" name="skip_password" value="1">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i>建立用戶
                            </button>
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
    $(document).ready(function() {
        // 將學號自動轉為大寫
        $('#student_id').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>
@endpush