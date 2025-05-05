@extends('layouts.app')

@section('title', '更新課表')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card fade-in">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-arrow-clockwise me-2"></i>更新課表
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('classroom.refresh') }}" id="refreshForm">
                        @csrf
                        <div class="mb-3">
                            <label for="smtr" class="form-label">
                                <i class="bi bi-calendar3 me-1"></i>學期
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                <input type="number" class="form-control @error('smtr') is-invalid @enderror" id="smtr"
                                    name="smtr" value="{{ old('smtr', '1132') }}" required>
                                @error('smtr')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>請輸入 4 位數字的學期代碼，例如：1132 代表 113 學年度第 2 學期
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="bi bi-cloud-download me-1"></i>更新課表
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 fade-in">
                <div class="card-header bg-light">
                    <i class="bi bi-info-circle me-2"></i>使用說明
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading mb-3">
                            <i class="bi bi-lightbulb me-2"></i>如何更新課表
                        </h5>
                        <ol class="mb-0">
                            <li class="mb-2">確認您要更新的學期代碼（例如：1132 表示 113 學年度第 2 學期）</li>
                            <li class="mb-2">在上方的表單中輸入學期代碼</li>
                            <li class="mb-2">點擊「更新課表」按鈕</li>
                            <li>等待系統完成課表資料的更新</li>
                        </ol>
                    </div>
                    <div class="alert alert-warning">
                        <h5 class="alert-heading mb-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>注意事項
                        </h5>
                        <ul class="mb-0">
                            <li class="mb-2">課表更新可能需要一些時間，請耐心等待</li>
                            <li class="mb-2">更新過程中請勿關閉瀏覽器或重新整理頁面</li>
                            <li>更新完成後會自動顯示成功或錯誤訊息</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('refreshForm');
        const updateBtn = document.getElementById('updateBtn');
        
        form.addEventListener('submit', function() {
            // 顯示載入中的效果
            updateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>更新中，請稍候...';
            updateBtn.disabled = true;
        });
    });
</script>
@endpush