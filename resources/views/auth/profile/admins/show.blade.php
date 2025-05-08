@extends('layouts.app')

@section('title', '用戶詳情')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">用戶詳情</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">姓名</label>
                        <p class="form-control-static">{{ $user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">學號</label>
                        <p class="form-control-static">{{ $user->student_id }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">電子郵件</label>
                        <p class="form-control-static">{{ $user->email }}</p>
                    </div>

                    @if($user->role !== 'admin')
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.users.edit', $user) }}" class="btn btn-primary">編輯資料</a>
                        
                        <form action="{{ route('profile.users.delete', $user) }}" method="POST" onsubmit="return confirm('確定要刪除此用戶嗎？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">刪除用戶</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection