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

            @php
                // 按樓層分組教室
                $floorClassrooms = [];
                foreach ($classrooms as $classroom) {
                    // 假設教室代碼格式為 [學院代碼][樓層][編號]，如 A101、A205 等
                    if (strlen($classroom->code) >= 2) {
                        $floor = substr($classroom->code, 1, 1);
                        if (!isset($floorClassrooms[$floor])) {
                            $floorClassrooms[$floor] = [];
                        }
                        $floorClassrooms[$floor][] = $classroom;
                    } else {
                        // 處理不符合預期格式的教室代碼
                        if (!isset($floorClassrooms['其他'])) {
                            $floorClassrooms['其他'] = [];
                        }
                        $floorClassrooms['其他'][] = $classroom;
                    }
                }
                // 排序樓層
                ksort($floorClassrooms);
            @endphp

            @foreach($floorClassrooms as $floor => $floorRooms)
                <div class="mt-4">
                    <h4 class="border-bottom pb-2 mb-3">{{ $currentBuilding }}{{ $floor }}樓</h4>
                    <div class="row">
                        @foreach($floorRooms as $classroom)
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
                    </div>
                </div>
            @endforeach

            @if(count($classrooms) === 0)
                <div class="col-12 text-center mt-4">
                    <p class="text-muted">沒有找到教室資料</p>
                </div>
            @endif
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