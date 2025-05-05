@extends('layouts.app')

@push('styles')
<style>
    .classroom-title {
        font-size: 1.25rem;
        font-weight: 500;
    }

    .classroom-description {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #28a745;
    }
    
    .alert-danger {
        border-left-color: #dc3545;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
    }
    
    .special-classroom {
        position: relative;
        border-left: 4px solid #0d6efd;
    }
    
    .time-slot {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
    
    .time-slot-current {
        background-color: #e9f5ff;
        border-left: 4px solid #0d6efd;
    }
    
    .refresh-icon {
        transition: transform 0.5s;
    }
    
    .refresh-icon.rotating {
        transform: rotate(360deg);
    }
    
    .time-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        margin-right: 5px;
        border-radius: 50%;
    }
    
    .time-indicator-current {
        background-color: #0d6efd;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="card fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-door-open me-2"></i>教室開門時間</h5>
                <span class="badge bg-info text-dark">目前學期：{{ $currentSemester }}</span>
            </div>
            <button class="btn btn-sm btn-primary" onclick="refreshPage()">
                <i class="bi bi-arrow-clockwise me-1 refresh-icon" id="refresh-icon"></i> 刷新
            </button>
        </div>
        <div class="card-body">     
            @if(count($classrooms) > 0)
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach($classrooms as $classroom)
                        <div class="col">
                            <div class="card h-100 special-classroom">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ $classroom->code }} 教室</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="classroom-title">{{ $classroom->name }}</div>
                                    </div>
                                    
                                    <div class="time-slot {{ $currentTimeSlot == 'morning' ? 'time-slot-current' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <span class="time-indicator {{ $currentTimeSlot == 'morning' ? 'time-indicator-current' : '' }}"></span>
                                                <strong>上午時段 (07:30-11:30)</strong>
                                            </div>
                                            @if($currentTimeSlot == 'morning')
                                                <span class="badge bg-primary">目前時段</span>
                                            @endif
                                        </div>
                                        <div class="alert {{ $classroomSchedules[$classroom->code]['morning'] ? 'alert-danger' : 'alert-success' }} mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi {{ $classroomSchedules[$classroom->code]['morning'] ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                                                    {{ $classroomSchedules[$classroom->code]['morning'] ? '需開門' : '無需開門' }}
                                                </div>
                                                <span class="badge {{ $classroomSchedules[$classroom->code]['morning'] ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $classroomSchedules[$classroom->code]['morning'] ? '有課程' : '無課程' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="time-slot {{ $currentTimeSlot == 'afternoon' ? 'time-slot-current' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <span class="time-indicator {{ $currentTimeSlot == 'afternoon' ? 'time-indicator-current' : '' }}"></span>
                                                <strong>下午時段 (11:30-18:00)</strong>
                                            </div>
                                            @if($currentTimeSlot == 'afternoon')
                                                <span class="badge bg-primary">目前時段</span>
                                            @endif
                                        </div>
                                        <div class="alert {{ $classroomSchedules[$classroom->code]['afternoon'] ? 'alert-danger' : 'alert-success' }} mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi {{ $classroomSchedules[$classroom->code]['afternoon'] ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                                                    {{ $classroomSchedules[$classroom->code]['afternoon'] ? '需開門' : '無需開門' }}
                                                </div>
                                                <span class="badge {{ $classroomSchedules[$classroom->code]['afternoon'] ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $classroomSchedules[$classroom->code]['afternoon'] ? '有課程' : '無課程' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="time-slot {{ $currentTimeSlot == 'evening' ? 'time-slot-current' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <span class="time-indicator {{ $currentTimeSlot == 'evening' ? 'time-indicator-current' : '' }}"></span>
                                                <strong>晚上時段 (18:00-21:40)</strong>
                                            </div>
                                            @if($currentTimeSlot == 'evening')
                                                <span class="badge bg-primary">目前時段</span>
                                            @endif
                                        </div>
                                        <div class="alert {{ $classroomSchedules[$classroom->code]['evening'] ? 'alert-danger' : 'alert-success' }} mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi {{ $classroomSchedules[$classroom->code]['evening'] ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                                                    {{ $classroomSchedules[$classroom->code]['evening'] ? '需開門' : '無需開門' }}
                                                </div>
                                                <span class="badge {{ $classroomSchedules[$classroom->code]['evening'] ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $classroomSchedules[$classroom->code]['evening'] ? '有課程' : '無課程' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    目前沒有220, 221, 319教室的資料，請確認是否已正確設置教室。
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // 自動每5分鐘刷新一次
        setTimeout(function() {
            refreshPage();
        }, 5 * 60 * 1000);
    });
    
    function refreshPage() {
        let icon = document.getElementById('refresh-icon');
        icon.classList.add('rotating');
        
        setTimeout(function() {
            window.location.reload();
        }, 500);
    }
</script>
@endpush