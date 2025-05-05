@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-display me-2"></i>教室使用狀態</h5>
                <span class="badge bg-info text-dark">目前學期：{{ $currentSemester }}</span>
            </div>
            <button class="btn btn-sm btn-primary" onclick="refreshStatus()">
                <i class="bi bi-arrow-clockwise me-1"></i> 刷新
            </button>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h6 class="mb-3 text-muted"><i class="bi bi-building me-2"></i>選擇學院：</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($buildings as $code => $name)
                        <a href="{{ route('classroom.status', ['building' => $code]) }}" 
                           class="btn {{ $building == $code ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $name }} ({{ $code }})
                        </a>
                    @endforeach
                </div>
            </div>

            @if(count($floorClassrooms) > 0)
                @foreach($floorClassrooms as $floor => $floorRooms)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">
                            <i class="bi bi-layers me-2"></i>{{ $floor }}樓
                        </h5>
                        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-2 mt-2">
                            @foreach($floorRooms as $classroom)
                                @php
                                    $isFree = empty($busyClassrooms[$classroom->code]);
                                @endphp
                                <div class="col">
                                    <button 
                                        class="btn w-100 h-100 {{ $isFree ? 'btn-success' : 'btn-danger' }}" 
                                        data-classroom-code="{{ $classroom->code }}"
                                        data-classroom-name="{{ $classroom->name }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#diskReplacementModal">
                                        <div class="text-center">
                                            <h6 class="mb-1 fw-bold">{{ $classroom->code }}</h6>
                                            <p class="mb-0 small">{{ $classroom->name }}</p>
                                            <span class="badge {{ $isFree ? 'bg-success' : 'bg-danger' }} mt-1">
                                                {{ $isFree ? '未使用' : '上課中' }}
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>此學院目前沒有教室資料。
                </div>
            @endif
        </div>
    </div>
</div>

<!-- 硬碟更換表單模態框 -->
<div class="modal fade" id="diskReplacementModal" tabindex="-1" aria-labelledby="diskReplacementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diskReplacementModalLabel">硬碟更換記錄</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
            </div>
            <form action="{{ route('disk-replacement.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <h5 class="classroom-info">教室：<span id="classroomCode"></span> <span id="classroomName" class="text-muted small"></span></h5>
                        <input type="hidden" name="classroom_code" id="classroom_code_input">
                    </div>
                    <div class="mb-3">
                        <label for="issue" class="form-label">問題描述</label>
                        <textarea class="form-control" id="issue" name="issue" rows="3" placeholder="請描述問題..."></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="disk_replaced" name="disk_replaced" checked>
                        <label class="form-check-label" for="disk_replaced">已更換硬碟</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">儲存記錄</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // 初始化模態框事件
        $('#diskReplacementModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var classroomCode = button.data('classroom-code');
            var classroomName = button.data('classroom-name');
            
            $('#classroomCode').text(classroomCode);
            $('#classroomName').text(classroomName);
            $('#classroom_code_input').val(classroomCode);
        });
        
        window.refreshStatus = function() {
            location.reload();
        };
    });
</script>
@endpush