@extends('layouts.app')

@section('title', '儀表板')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow animate__animated animate__fadeIn">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-speedometer2 me-2"></i>
                            <span>儀表板</span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-display text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5 class="card-title">教室狀態</h5>
                                        <p class="card-text text-muted">查看各教室目前的使用情況及排程</p>
                                        <a href="{{ route('classroom.status') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-arrow-right me-1"></i>立即查看
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-hdd text-success" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5 class="card-title">硬碟更換記錄</h5>
                                        <p class="card-text text-muted">查看硬碟更換的歷史記錄及相關資訊</p>
                                        <a href="{{ route('disk-replacement.index') }}" class="btn btn-outline-success">
                                            <i class="bi bi-arrow-right me-1"></i>立即查看
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->role === 'admin' || Auth::user()->role == 'super_admin')
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="bi bi-arrow-clockwise text-warning" style="font-size: 3rem;"></i>
                                                </div>
                                                <h5 class="card-title">課表更新</h5>
                                                <p class="card-text text-muted">更新教室課表資料</p>
                                                <a href="{{ route('classroom.refresh.form') }}" class="btn btn-outline-warning">
                                                    <i class="bi bi-arrow-right me-1"></i>前往更新
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                                                </div>
                                                <h5 class="card-title">用戶管理</h5>
                                                <p class="card-text text-muted">管理系統用戶資料和權限</p>
                                                <a href="{{ route('profile.users.index') }}" class="btn btn-outline-info">
                                                    <i class="bi bi-arrow-right me-1"></i>進入管理
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth

                            @auth
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="bi bi-person-circle text-secondary" style="font-size: 3rem;"></i>
                                            </div>
                                            <h5 class="card-title">個人資料</h5>
                                            <p class="card-text text-muted">查看和編輯您的個人資料</p>
                                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-right me-1"></i>前往設定
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection