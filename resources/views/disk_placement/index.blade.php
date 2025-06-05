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
    
    /* 修復分頁按鈕樣式 */
    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }
    
    /* 為分頁添加正確的箭頭圖標 */
    .pagination svg {
        width: 20px;
        height: 20px;
    }
    
    /* 如果需要完全隱藏SVG圖標並使用純文字 */
    .pagination svg {
        display: none;
    }
    
    /* 自定義上一頁按鈕 */
    .pagination li:first-child a::before,
    .pagination li:first-child span::before {
        content: '«';
        margin-right: 5px;
    }
    
    /* 自定義下一頁按鈕 */
    .pagination li:last-child a::after,
    .pagination li:last-child span::after {
        content: '»';
        margin-left: 5px;
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
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
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
                                <select name="classroom_code" class="form-select">
                                    <option value="">所有教室</option>
                                    @foreach($classroomCodes as $code)
                                        <option value="{{ $code }}" {{ $request->classroom_code == $code ? 'selected' : '' }}>
                                            {{ $code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="form-label">用戶名稱</label>
                                <select name="user_name" class="form-select">
                                    <option value="">所有用戶</option>
                                    @foreach($userNames as $userName)
                                        <option value="{{ $userName }}" {{ $request->user_name == $userName ? 'selected' : '' }}>
                                            {{ $userName }}
                                        </option>
                                    @endforeach
                                </select>
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
                            @if(isset($canManage) && $canManage)
                            <th scope="col"><i class="bi bi-gear me-1"></i>操作</th>
                            @endif
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
                                @if(isset($canManage) && $canManage)
                                <td data-label="操作" class="text-nowrap">
                                    <button type="button" class="btn btn-sm btn-warning me-1" onclick="openEditModal({{ $replacement->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $replacement->id }}, '{{ $replacement->classroom_code }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr class="no-records-row">
                                <td colspan="{{ isset($canManage) && $canManage ? 7 : 6 }}" class="text-center py-4">
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
                <nav aria-label="分頁導航">
                    <ul class="pagination">
                        {{-- 上一頁連結 --}}
                        @if ($replacements->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo; 上一頁</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $replacements->previousPageUrl() }}" rel="prev">&laquo; 上一頁</a>
                            </li>
                        @endif

                        {{-- 頁碼 --}}
                        @foreach ($replacements->getUrlRange(1, $replacements->lastPage()) as $page => $url)
                            @if ($page == $replacements->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- 下一頁連結 --}}
                        @if ($replacements->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $replacements->nextPageUrl() }}" rel="next">下一頁 &raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">下一頁 &raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

@if(isset($canManage) && $canManage)
<!-- 編輯硬碟更換記錄模態框 -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>編輯硬碟更換記錄
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_classroom_code" class="form-label">教室代碼 <span class="text-danger">*</span></label>
                            <select id="edit_classroom_code" name="classroom_code" class="form-select" required>
                                <option value="">選擇教室</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->code }}">
                                        {{ $classroom->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_replaced_at" class="form-label">更換日期 <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="edit_replaced_at" name="replaced_at" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_user_name" class="form-label">操作人員 <span class="text-danger">*</span></label>
                            <select id="edit_user_name" name="user_name" class="form-select" required>
                                <option value="">選擇操作人員</option>
                                @foreach($users as $user)
                                    <option value="{{ $user }}">{{ $user }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_smtr" class="form-label">學期 <span class="text-danger">*</span></label>
                            <select id="edit_smtr" name="smtr" class="form-select" required>
                                <option value="">選擇學期</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="edit_disk_replaced" name="disk_replaced">
                        <label class="form-check-label" for="edit_disk_replaced">
                            硬碟已更換
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_issue" class="form-label">問題描述</label>
                        <textarea id="edit_issue" name="issue" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 刪除確認模態框 -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>確定要刪除教室 <span id="deleteClassroomCode" class="fw-bold"></span> 的硬碟更換記錄嗎？</p>
                <p class="text-danger"><small>此操作無法復原</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">確認刪除</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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

@if(isset($canManage) && $canManage)
// 開啟編輯模態框
function openEditModal(id) {
    // 重置表單
    document.getElementById('editForm').reset();
    
    // 設置表單動作
    document.getElementById('editForm').action = '{{ url("disk-replacement") }}/' + id;
    
    // 通過 AJAX 獲取記錄數據
    fetch('{{ url("disk-replacement") }}/' + id + '/edit')
        .then(response => {
            if (!response.ok) {
                throw new Error('獲取數據失敗: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // 填充表單
            document.getElementById('edit_classroom_code').value = data.classroom_code;
            document.getElementById('edit_replaced_at').value = data.replaced_at_formatted;
            document.getElementById('edit_user_name').value = data.user_name;
            document.getElementById('edit_smtr').value = data.smtr;
            document.getElementById('edit_disk_replaced').checked = data.disk_replaced;
            document.getElementById('edit_issue').value = data.issue || '';
            
            // 顯示模態框
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('獲取記錄數據失敗: ' + error.message);
        });
}

// 確認刪除函數
function confirmDelete(id, classroomCode) {
    document.getElementById('deleteClassroomCode').textContent = classroomCode;
    document.getElementById('deleteForm').action = '{{ url("disk-replacement") }}/' + id;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
@endif
</script>
@endpush
@endsection