@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            教室使用狀態
            <button class="btn btn-sm btn-primary float-end" onclick="refreshStatus()">刷新</button>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5 class="mb-3">選擇學院：</h5>
                <div class="btn-group d-flex flex-wrap">
                    @foreach($buildings as $code => $name)
                        <a href="{{ route('classroom.status', ['building' => $code]) }}" 
                           class="btn {{ $currentBuilding == $code ? 'btn-primary' : 'btn-outline-primary' }} mb-2">
                            {{ $name }} ({{ $code }})
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="row" id="classrooms-container">
                @if($classrooms->count() > 0)
                    @foreach($classrooms as $classroom)
                        @php
                            $isBusy = isset($busyClassrooms[$classroom->code]) && $busyClassrooms[$classroom->code] === 'Y';
                            $bgColor = $isBusy ? 'bg-danger' : 'bg-success';
                            $status = $isBusy ? '上課中' : '未使用';
                        @endphp
                        <div class="col-md-3 col-lg-2 mb-3">
                            <div class="card {{ $bgColor }} text-white shadow classroom-card" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#diskReplacementModal" 
                                 data-classroom-code="{{ $classroom->code }}"
                                 style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold">{{ $classroom->code }}</h5>
                                    <p class="card-text mb-0">{{ $status }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p class="text-muted">沒有找到教室資料</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-3 text-center">
        <span class="badge bg-danger p-2">紅色 = 上課中</span>
        <span class="badge bg-success p-2 ms-3">綠色 = 未使用</span>
    </div>

    <div class="mt-2 text-center">
        <p>當前顯示: {{ isset($currentBuilding) && isset($buildings[$currentBuilding]) ? $buildings[$currentBuilding] : '未知學院' }}</p>
    </div>
</div>

<!-- 硬碟更換模態框 -->
<div class="modal fade" id="diskReplacementModal" tabindex="-1" aria-labelledby="diskReplacementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diskReplacementModalLabel">硬碟更換記錄</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="diskReplacementForm" method="POST" action="{{ route('disk-replacement.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="classroom_code" id="classroom_code_input">
                    
                    <div class="mb-3">
                        <label for="issue" class="form-label">問題描述 (若無問題，可留空)</label>
                        <textarea class="form-control" id="issue" name="issue" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="disk_replaced" name="disk_replaced" checked>
                        <label class="form-check-label" for="disk_replaced">已更換硬碟</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshStatus() {
    location.reload();
}

// 自動每分鐘刷新一次
setTimeout(function() {
    refreshStatus();
}, 60000);

// 處理模態框顯示時的資料傳遞
document.addEventListener('DOMContentLoaded', function() {
    var diskReplacementModal = document.getElementById('diskReplacementModal');
    diskReplacementModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var classroomCode = button.getAttribute('data-classroom-code');
        var classroomCodeInput = document.getElementById('classroom_code_input');
        classroomCodeInput.value = classroomCode;
        
        document.getElementById('diskReplacementModalLabel').textContent = '硬碟更換記錄 - ' + classroomCode;
    });
});
</script>
@endpush
@endsection