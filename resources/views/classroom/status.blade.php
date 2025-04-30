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
                            <div class="card {{ $bgColor }} text-white shadow">
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
        <p>當前顯示: {{ $buildings[$currentBuilding] }}</p>
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
</script>
@endpush
@endsection