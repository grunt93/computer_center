@extends('layouts.app')

@push('styles')
<style>
    .filter-card {
        transition: all 0.3s ease;
    }
    
    .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="card fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-hdd me-2"></i>硬碟更換記錄
            </h5>
            <button class="btn btn-sm btn-primary" onclick="toggleFilterCard()">
                <i class="bi bi-funnel me-1"></i>過濾選項
            </button>
        </div>
        
        <div class="card-body">
            <div class="card mb-4 filter-card" id="filterCard">
                <div class="card-body">
                    <form action="{{ route('disk-replacement.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">學期</label>
                                <select name="smtr" class="form-select">
                                    <option value="">所有學期</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester }}" {{ $request->smtr == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">建築物</label>
                                <select name="building" class="form-select">
                                    <option value="">所有建築物</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building }}" {{ $request->building == $building ? 'selected' : '' }}>{{ $building }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">教室代碼</label>
                                <input type="text" name="classroom_code" class="form-control" value="{{ $request->classroom_code }}">
                            </div>
                            
                            <!-- 新增用戶名稱查詢欄位 -->
                            <div class="col-md-3">
                                <label class="form-label">用戶名稱</label>
                                <input type="text" name="user_name" class="form-control" value="{{ $request->user_name }}" placeholder="輸入用戶名稱">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">起始日期</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $request->start_date }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">結束日期</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $request->end_date }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search"></i> 搜尋
                                </button>
                                <a href="{{ route('disk-replacement.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> 清除篩選
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col"><i class="bi bi-display me-1"></i>教室</th>
                            <th scope="col"><i class="bi bi-check-circle me-1"></i>狀態</th>
                            <th scope="col"><i class="bi bi-calendar me-1"></i>更換日期</th>
                            <th scope="col"><i class="bi bi-calendar3 me-1"></i>學期</th>
                            <th scope="col"><i class="bi bi-person me-1"></i>操作人員</th>
                            <th scope="col"><i class="bi bi-chat-left-text me-1"></i>問題描述</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($replacements as $replacement)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $replacement->classroom_code }}</span>
                                </td>
                                <td>
                                    @if($replacement->disk_replaced)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>已更換
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>未更換
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $replacement->replaced_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $replacement->smtr }}</td>
                                <td>{{ $replacement->user->name ?? '未知' }}</td>
                                <td>
                                    @if($replacement->issue)
                                        <button type="button" class="btn btn-sm btn-info view-issue" 
                                                data-bs-toggle="modal" data-bs-target="#issueModal" 
                                                data-issue="{{ $replacement->issue }}"
                                                data-classroom="{{ $replacement->classroom_code }}"
                                                data-date="{{ $replacement->replaced_at->format('Y-m-d H:i') }}">
                                            <i class="bi bi-eye me-1"></i>查看問題
                                        </button>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-dash-circle me-1"></i>無
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>沒有找到相關記錄
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($replacements->count() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $replacements->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- 問題描述模態框 -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">
                    <i class="bi bi-chat-right-text me-2"></i>問題描述
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-body bg-light">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong><i class="bi bi-display me-2"></i>教室：</strong>
                                    <span id="classroomText" class="badge bg-secondary"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <strong><i class="bi bi-calendar me-2"></i>記錄日期：</strong>
                                    <span id="dateText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-chat-left-text me-2"></i>問題描述：</strong>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 300px; overflow-y: auto; padding: 10px; border-radius: 4px;">
                            <p id="issueText" style="white-space: pre-wrap;" class="mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>關閉
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 初始化隱藏過濾卡片（如果網址中沒有搜尋參數）
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.toString() === '') {
        document.getElementById('filterCard').style.display = 'none';
    }
    
    // 問題描述模態框
    const issueModal = document.getElementById('issueModal');
    
    issueModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const issue = button.getAttribute('data-issue');
        const classroom = button.getAttribute('data-classroom');
        const date = button.getAttribute('data-date');
        
        document.getElementById('issueText').textContent = issue;
        document.getElementById('classroomText').textContent = classroom;
        document.getElementById('dateText').textContent = date;
    });
});

// 切換過濾選項卡片顯示/隱藏
function toggleFilterCard() {
    const filterCard = document.getElementById('filterCard');
    if (filterCard.style.display === 'none') {
        filterCard.style.display = 'block';
    } else {
        filterCard.style.display = 'none';
    }
}
</script>
@endpush
@endsection