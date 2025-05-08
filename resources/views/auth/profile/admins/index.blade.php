@extends('layouts.app')

@section('title', '用戶管理')

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    @media (max-width: 767.98px) {
        .table-responsive-sm thead {
            display: none;
        }
        
        .table-responsive-sm tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .table-responsive-sm tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table-responsive-sm tbody td:last-child {
            border-bottom: none;
        }
        
        .table-responsive-sm tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>用戶管理</h5>
                    <a href="{{ route('profile.users.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-person-plus me-1"></i>新增用戶
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- 搜尋表單 -->
                    <form action="{{ route('profile.users.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" placeholder="搜尋姓名..." value="{{ request('name') }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <input type="text" name="student_id" class="form-control" placeholder="搜尋學號..." value="{{ request('student_id') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>搜尋
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">姓名</th>
                                    <th scope="col">學號</th>
                                    <th scope="col">電子郵件</th>
                                    <th scope="col">角色</th>
                                    <th scope="col">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td data-label="姓名">{{ $user->name }}</td>
                                    <td data-label="學號">{{ $user->student_id }}</td>
                                    <td data-label="電子郵件">{{ $user->email }}</td>
                                    <td data-label="角色">
                                        @if($user->role === 'super_admin')
                                            <span class="badge bg-primary">超級管理員</span>
                                        @elseif($user->role === 'admin')
                                            <span class="badge bg-danger">管理員</span>
                                        @else
                                            <span class="badge bg-info">職員</span>
                                        @endif
                                    </td>
                                    <td data-label="操作">
                                        @if(Auth::user()->role === 'super_admin' && $user->id !== Auth::id())
                                            <a href="{{ route('profile.users.show', $user) }}" class="btn btn-sm btn-info mb-1">
                                                <i class="bi bi-eye me-1"></i>查看
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection