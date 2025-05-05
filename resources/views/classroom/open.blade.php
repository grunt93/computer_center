@extends('layouts.app')

@push('styles')
<style>
    .classroom-title {
        font-size: 1.25rem;
        font-weight: 500;
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
    
    .special-classroom {
        position: relative;
        border-left: 4px solid #0d6efd;
    }
    
    .time-slot {
        margin-bottom: 10px;
        padding: 15px;
        border-radius: 5px;
        background-color: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
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
        box-shadow: 0 0 5px #0d6efd;
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
            <div class="text-end">
                <div id="current-time" class="fw-bold"></div>
                <small class="text-muted">系統時間</small>
            </div>
        </div>
        <div class="card-body">     
            @php
                $timeSlotNames = [
                    'morning' => '上午時段 (07:30-11:30)',
                    'afternoon' => '下午時段 (11:30-18:00)',
                    'evening' => '晚上時段 (18:00-21:40)'
                ];
                
                $timeSlotIcons = [
                    'morning' => 'sun',
                    'afternoon' => 'sun-fill',
                    'evening' => 'moon'
                ];
            @endphp
        
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                目前顯示 <strong>{{ isset($currentTimeSlot) && isset($timeSlotNames[$currentTimeSlot]) ? $timeSlotNames[$currentTimeSlot] : '未知時段' }}</strong> 的教室開門狀態，
                若有課程安排，請在上課前到教室開門。
            </div>
        
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
                                    
                                    <div class="time-slot">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="time-indicator time-indicator-current"></span>
                                                <strong>
                                                    <i class="bi bi-{{ $timeSlotIcons[$currentTimeSlot] }} me-1"></i>
                                                    {{ $timeSlotNames[$currentTimeSlot] }}
                                                </strong>
                                            </div>
                                            <span class="badge bg-primary">目前時段</span>
                                        </div>
                                        <div class="alert {{ $classroomSchedules[$classroom->code][$currentTimeSlot] ? 'alert-danger' : 'alert-success' }} mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi {{ $classroomSchedules[$classroom->code][$currentTimeSlot] ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                                                    {{ $classroomSchedules[$classroom->code][$currentTimeSlot] ? '需開門' : '無需開門' }}
                                                </div>
                                                <span class="badge {{ $classroomSchedules[$classroom->code][$currentTimeSlot] ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $classroomSchedules[$classroom->code][$currentTimeSlot] ? '有課程' : '無課程' }}
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
        // 更新當前時間
        function updateCurrentTime() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            $('#current-time').text(now.toLocaleTimeString('zh-TW', options));
        }
        
        // 初始更新和設置定時器
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
    });

    function refreshPage() {
        let icon = $('#refresh-icon');
        icon.addClass('rotating');
        
        window.location.reload();
    }
</script>
@endpush