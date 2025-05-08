@extends('layouts.app')

@section('title', '用戶詳情')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">用戶詳情</h5>
                    <a href="{{ route('profile.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>返回用戶列表
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                    <div class="mb-3">
                        <label class="form-label">角色</label>
                        <p class="form-control-static">
                            @if($user->role === 'super_admin')
                                <span class="badge bg-primary">超級管理員</span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-danger">管理員</span>
                            @else
                                <span class="badge bg-info">職員</span>
                            @endif
                        </p>
                    </div>

                    @if(Auth::user()->role === 'super_admin' && $user->id !== Auth::id())
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.users.edit', $user) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>編輯資料
                        </a>
                        
                        <form action="{{ route('profile.users.delete', $user) }}" method="POST" onsubmit="return confirm('確定要刪除此用戶嗎？此操作無法撤銷。')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>刪除用戶
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection