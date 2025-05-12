@extends('layouts.app')

@section('title', '硬碟更換記錄')

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
    
    /* 在手機上優化表格顯示 */
    @media (max-width: 767.98px) {
        /* 將表格轉為卡片式顯示 */
        .table-responsive-card thead {
            display: none;
        }
        
        .table-responsive-card tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        /* 特別處理無記錄的情況 */
        .table-responsive-card tbody tr.no-records-row {
            display: block;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        
        .table-responsive-card tbody tr.no-records-row td {
            display: block;
            padding: 1rem;
            border: none;
        }
        
        .table-responsive-card tbody tr.no-records-row td:before {
            content: none;
        }
        
        .table-responsive-card tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table-responsive-card tbody td:last-child {
            border-bottom: none;
        }
        
        .table-responsive-card tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            min-width: 30%;
        }
        
        /* 改善按鈕在手機上的可點擊區域 */
        .btn-sm {
            padding: 0.375rem 0.75rem;
        }
        
        /* 篩選表單在手機上更緊湊 */
        .filter-form-mobile .form-label {
            margin-bottom: 0.25rem;
        }
        
        .filter-form-mobile .form-control,
        .filter-form-mobile .form-select {
            font-size: 0.95rem;
            padding: 0.375rem 0.5rem;
        }
        
        /* 在行動裝置上改善分頁按鈕 */
        .pagination .page-link {
            padding: 0.375rem 0.75rem;
        }
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
                <i class="bi bi-funnel me-1"></i><span class="d-none d-sm-inline">過濾選項</span>
            </button>
        </div>
        
        <div class="card-body">
            <div class="card mb-4 filter-card" id="filterCard">
                <div class="card-body">
                    <form action="{{ route('disk-replacement.index') }}" method="GET" class="filter-form-mobile">
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <label class="form-label">學期</label>
                                <select name="smtr" class="form-select">
                                    <option value="">所有學期</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester }}" {{ $request->smtr == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">建築物</label>
                                <select name="building" class="form-select">
                                    <option value="">所有建築物</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building }}" {{ $request->building == $building ? 'selected' : '' }}>{{ $building }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">教室代碼</label>
                                <input type="text" name="classroom_code" class="form-control" value="{{ $request->classroom_code }}">
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <label class="form-label">用戶名稱</label>
                                <input type="text" name="user_name" class="form-control" value="{{ $request->user_name }}" placeholder="輸入用戶名稱">
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <label class="form-label">起始日期</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $request->start_date }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">結束日期</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $request->end_date }}">
                            </div>
                            <div class="col-12 col-md-6 d-flex mt-3">
                                <button type="submit" class="btn btn-primary me-2 flex-grow-1 flex-md-grow-0">
                                    <i class="bi bi-search"></i><span class="ms-1">搜尋</span>
                                </button>
                                <a href="{{ route('disk-replacement.index') }}" class="btn btn-secondary flex-grow-1 flex-md-grow-0">
                                    <i class="bi bi-x-circle"></i><span class="ms-1">清除</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <!-- 添加 table-responsive-card 類名以支持卡片式顯示 -->
                <table class="table table-striped table-hover table-responsive-card">
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
                                <td data-label="教室">
                                    <span class="badge bg-secondary">{{ $replacement->classroom_code }}</span>
                                </td>
                                <td data-label="狀態">
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
                                <td data-label="更換日期">{{ $replacement->replaced_at->format('Y-m-d H:i') }}</td>
                                <td data-label="學期">{{ $replacement->smtr }}</td>
                                <td data-label="操作人員">{{ $replacement->user_name }}</td>
                                <td data-label="問題描述">
                                    @if($replacement->issue)
                                        <button type="button" class="btn btn-sm btn-info view-issue w-100 w-md-auto" 
                                                data-bs-toggle="modal" data-bs-target="#issueModal" 
                                                data-issue="{{ $replacement->issue }}"
                                                data-classroom="{{ $replacement->classroom_code }}"
                                                data-date="{{ $replacement->replaced_at->format('Y-m-d H:i') }}">
                                            <i class="bi bi-eye me-1"></i>查看
                                        </button>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-dash-circle me-1"></i>無
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="no-records-row">
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