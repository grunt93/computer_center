@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- 基本資料卡片 -->
            <div class="card mb-4">
                <div class="card-header">編輯用戶資料</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">姓名</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">學號</label>
                            <input id="student_id" type="text" class="form-control @error('student_id') is-invalid @enderror" name="student_id" value="{{ old('student_id', $user->student_id) }}" required>
                            @error('student_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">角色</label>
                            <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>職員</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>管理員</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">更新資料</button>
                    </form>
                </div>
            </div>

            <!-- 電子郵件卡片 -->
            <div class="card mb-4">
                <div class="card-header">更新電子郵件</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.users.email.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="email" class="form-label">電子郵件</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">更新電子郵件</button>
                    </form>
                </div>
            </div>

            <!-- 密碼卡片 -->
            <div class="card">
                <div class="card-header">重設密碼</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.users.password.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password" class="form-label">新密碼</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">確認密碼</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">更新密碼</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection