@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            硬碟更換記錄
        </div>
        <div class="card-body">
            <div class="mb-4">
                <form action="{{ route('disk-replacement.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="smtr" class="form-label">學期</label>
                        <select name="smtr" id="smtr" class="form-select">
                            <option value="">所有學期</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ request('smtr') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="building" class="form-label">學院</label>
                        <select name="building" id="building" class="form-select">
                            <option value="">所有學院</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building }}" {{ request('building') == $building ? 'selected' : '' }}>{{ $building }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="classroom_code" class="form-label">教室</label>
                        <input type="text" class="form-control" id="classroom_code" name="classroom_code" 
                               value="{{ request('classroom_code') }}" placeholder="輸入教室代碼">
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">開始日期</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">結束日期</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary me-2">搜尋</button>
                        <a href="{{ route('disk-replacement.index') }}" class="btn btn-outline-secondary">重設</a>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>教室</th>
                            <th>狀態</th>
                            <th>更換日期</th>
                            <th>學期</th>
                            <th>操作人員</th>
                            <th>問題描述</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($replacements as $replacement)
                            <tr>
                                <td>{{ $replacement->classroom_code }}</td>
                                <td>
                                    @if($replacement->disk_replaced)
                                        <span class="badge bg-success">已更換</span>
                                    @else
                                        <span class="badge bg-warning text-dark">未更換</span>
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
                                            查看問題
                                        </button>
                                    @else
                                        <span class="text-muted">無</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">沒有找到相關記錄</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $replacements->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- 問題描述模態框 - 加大尺寸並添加滾动條 -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- 加大模態框尺寸 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">問題描述</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>教室：</strong> <span id="classroomText"></span>
                </div>
                <div class="mb-3">
                    <strong>記錄日期：</strong> <span id="dateText"></span>
                </div>
                <div class="mb-3">
                    <strong>問題描述：</strong>
                </div>
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; padding: 15px; border-radius: 4px;">
                    <p id="issueText" style="white-space: pre-wrap;"></p> <!-- 保留換行符 -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 為所有「查看問題」按鈕添加事件監聽
    const issueModal = document.getElementById('issueModal');
    
    issueModal.addEventListener('show.bs.modal', function(event) {
        // 獲取觸發按鈕
        const button = event.relatedTarget;
        
        // 獲取按鈕中的資料
        const issue = button.getAttribute('data-issue');
        const classroom = button.getAttribute('data-classroom');
        const date = button.getAttribute('data-date');
        
        // 將資料填入模態框
        document.getElementById('issueText').textContent = issue;
        document.getElementById('classroomText').textContent = classroom;
        document.getElementById('dateText').textContent = date;
    });
});
</script>
@endpush
@endsection