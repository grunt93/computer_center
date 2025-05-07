@extends('layouts.app')

@section('title', '教室使用狀態')

@push('styles')
    <style>
        /* 改善按鈕在小螢幕上的顯示 */
        @media (max-width: 576px) {
            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            /* 確保按鈕等高且內容置中 */
            .action-buttons .btn {
                height: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        
        /* 增加按鈕間的間距 */
        .action-buttons {
            margin-top: 12px;
            margin-bottom: 16px;
        }

        /* 篩選狀態標記樣式優化 */
        .filter-badge {
            margin-top: 4px;
            display: inline-block;
        }

        /* 硬碟更換日期顯示樣式 */
        .disk-replacement-date {
            font-size: 0.75rem;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            line-height: 1.4;
        }
        
        /* 調整篩選狀態提示區塊的樣式 */
        .filter-status {
            margin-top: 16px;
        }
        
        /* 調整按鈕內容的間距 */
        .action-buttons .btn i {
            margin-right: 6px;
        }
        
        /* 篩選按鈕中的標記位置調整 */
        .filter-badge-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #0d6efd;
            border-radius: 50%;
            margin-left: 6px;
        }

        /* 新增平滑滾動效果 */
        html {
            scroll-behavior: smooth;
        }
        
        /* 教室按鈕點擊效果增強 */
        .btn:active {
            transform: scale(0.98);
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="card fade-in">
            <div class="card-header">
                <!-- 標題部分 -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="mb-0"><i class="bi bi-display me-2"></i>教室使用狀態</h5>
                        <span class="badge bg-info text-dark">目前學期：{{ $currentSemester }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="mb-3 text-muted"><i class="bi bi-building me-2"></i>選擇學院：</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($buildings as $code => $name)
                            <a href="{{ route('classroom.status', [
                                'building' => $code,
                                'filter_date' => $filterDate,
                                'need_replacement' => $showOnlyNeedReplacement ? 1 : 0
                            ]) }}" class="btn {{ $building == $code ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $name }} ({{ $code }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 按鈕部分，強制並排顯示 -->
                <div class="row" style="margin-bottom: 16px;">
                    <div class="col-6">
                        <button class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            <i class="bi bi-funnel me-1"></i> 篩選設定
                            @if($showOnlyNeedReplacement || $filterDate != now()->format('Y-m-d'))
                                <span class="badge bg-primary">已篩選</span>
                            @endif
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-primary w-100" onclick="refreshStatus()">
                            <i class="bi bi-arrow-clockwise me-1"></i> 刷新
                        </button>
                    </div>
                </div>

                <!-- 顯示目前篩選狀態 -->
                @if($showOnlyNeedReplacement || $filterDate != now()->format('Y-m-d'))
                    <div class="alert alert-info mb-4 filter-status">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="mb-2 mb-md-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>目前篩選：</strong>
                                <div class="d-block d-md-inline-block mt-2 mt-md-0">
                                    @if($filterDate != now()->format('Y-m-d'))
                                        <span class="badge bg-secondary me-2 filter-badge">日期: {{ $filterDate }}</span>
                                    @endif
                                    @if($showOnlyNeedReplacement)
                                        <span class="badge bg-warning text-dark filter-badge">僅顯示需更換硬碟的教室</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('classroom.status', ['building' => $building]) }}"
                                class="btn btn-sm btn-outline-secondary mt-2 mt-md-0">
                                <i class="bi bi-x-circle me-1"></i> 清除篩選
                            </a>
                        </div>
                    </div>
                @endif

                @if(count($floorClassrooms) > 0)
                    @foreach($floorClassrooms as $floor => $floorRooms)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2" id="floor-{{ $floor }}">
                                <i class="bi bi-layers me-2"></i>{{ $floor }}樓
                            </h5>
                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2 mt-2">
                                @foreach($floorRooms as $classroom)
                                    @php
                                        $isFree = empty($busyClassrooms[$classroom->code]);
                                    @endphp
                                    <div class="col">
                                        <button class="btn w-100 h-100 {{ $isFree ? 'btn-success' : 'btn-danger' }}"
                                            data-classroom-code="{{ $classroom->code }}" data-classroom-name="{{ $classroom->name }}"
                                            data-bs-toggle="modal" data-bs-target="#diskReplacementModal">
                                            <div class="text-center">
                                                <h6 class="mb-1 fw-bold">{{ $classroom->code }}</h6>
                                                <p class="mb-0 small">{{ $classroom->name }}</p>
                                                <span class="badge {{ $isFree ? 'bg-success' : 'bg-danger' }} mt-1">
                                                    {{ $isFree ? '未使用' : '上課中' }}
                                                </span>
                                                @if(isset($lastDiskReplacements[$classroom->code]))
                                                    <div class="disk-replacement-date">
                                                        <i class="bi bi-hdd me-1"></i>
                                                        上次更換:<br>{{ $lastDiskReplacements[$classroom->code] }}
                                                    </div>
                                                @endif
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

    <!-- 篩選設定模態框 -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="bi bi-funnel me-2"></i>篩選設定
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <form id="filterForm" method="GET" action="{{ route('classroom.status') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="filter_date" class="form-label">選擇日期：</label>
                            <input type="date" class="form-control" id="filter_date" name="filter_date"
                                value="{{ $filterDate }}" max="{{ date('Y-m-d') }}">
                            <small class="form-text text-muted">預設為今天，顯示此日期後未更換硬碟的教室</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="need_replacement" name="need_replacement"
                                value="1" {{ $showOnlyNeedReplacement ? 'checked' : '' }}>
                            <label class="form-check-label" for="need_replacement">
                                僅顯示需更換硬碟的教室
                            </label>
                        </div>
                        <input type="hidden" name="building" value="{{ $building }}">
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('classroom.status', ['building' => $building]) }}"
                            class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> 重設篩選
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter me-1"></i> 套用篩選
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 硬碟更換表單模態框 -->
    <div class="modal fade" id="diskReplacementModal" tabindex="-1" aria-labelledby="diskReplacementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diskReplacementModalLabel">硬碟更換記錄</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <form action="{{ route('disk-replacement.store') }}" method="POST" id="diskReplacementForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <h5 class="classroom-info">教室：<span id="classroomCode"></span> <span id="classroomName"
                                    class="text-muted small"></span></h5>
                            <input type="hidden" name="classroom_code" id="classroom_code_input">
                        </div>
                        <div class="mb-3">
                            <label for="issue" class="form-label">問題描述 (選填)</label>
                            <textarea class="form-control" id="issue" name="issue" rows="3"></textarea>
                            <small class="form-text text-muted">如無特殊問題，可留空</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="disk_replaced" name="disk_replaced" checked>
                            <label class="form-check-label" for="disk_replaced">已更換硬碟</label>
                        </div>
                        <!-- 隱藏字段保存當前狀態 -->
                        <input type="hidden" name="building" value="{{ $building }}">
                        <input type="hidden" name="filter_date" value="{{ $filterDate }}">
                        <input type="hidden" name="need_replacement" value="{{ $showOnlyNeedReplacement ? 1 : 0 }}">
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
        $(document).ready(function () {
            // 檢查是否是從表單提交返回的頁面
            const isFromSubmission = sessionStorage.getItem('isFromSubmission') === 'true';
            
            // 只有在從表單提交返回時才恢復滾動位置
            if (isFromSubmission && sessionStorage.getItem('scrollPosition')) {
                const savedPosition = parseInt(sessionStorage.getItem('scrollPosition'));
                $(window).scrollTop(savedPosition);
                
                // 延遲一下，確保頁面元素都已載入
                setTimeout(function() {
                    $(window).scrollTop(savedPosition);
                }, 200);
                
                // 清除標記，以便下次正常訪問頁面時不會自動滾動
                sessionStorage.removeItem('isFromSubmission');
            }
            
            // 初始化模態框事件
            $('#diskReplacementModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var classroomCode = button.data('classroom-code');
                var classroomName = button.data('classroom-name');

                $('#classroomCode').text(classroomCode);
                $('#classroomName').text(classroomName);
                $('#classroom_code_input').val(classroomCode);
                
                // 保存滾動位置
                sessionStorage.setItem('scrollPosition', $(window).scrollTop());
            });

            // 刷新頁面時也保存位置，但不標記為表單提交
            window.refreshStatus = function () {
                sessionStorage.setItem('scrollPosition', $(window).scrollTop());
                sessionStorage.setItem('isFromSubmission', 'false'); // 明確標記不是表單提交
                location.reload();
            };
            
            // 提交表單前保存滾動位置並標記為表單提交
            $('#diskReplacementForm').on('submit', function() {
                sessionStorage.setItem('scrollPosition', $(window).scrollTop());
                sessionStorage.setItem('isFromSubmission', 'true'); // 標記為表單提交
                
                // 同時保存當前篩選條件和樓層資訊
                var currentFloor = $('.border-bottom.pb-2:visible').first().text().trim();
                if (currentFloor) {
                    sessionStorage.setItem('currentFloor', currentFloor);
                }
                
                return true;
            });
        });
    </script>
@endpush