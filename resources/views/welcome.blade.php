@extends('layouts.app')

@section('title', '首頁')

@push('styles')
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 4rem 0;
            margin-top: -1.5rem;
            border-radius: 0 0 1.5rem 1.5rem;
        }

        .feature-card {
            height: 100%;
            transition: all 0.3s;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .section-header {
            margin-bottom: 2rem;
        }

        .section-header::after {
            content: '';
            display: block;
            width: 70px;
            height: 3px;
            background: #0d6efd;
            margin: 1rem auto 0;
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0d6efd;
        }

        .animate-fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s, transform 0.8s;
        }

        .animate-fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }

        .help-card {
            height: 100%;
        }

        .quick-link {
            display: block;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
            transition: all 0.3s;
        }

        .quick-link:hover {
            background-color: #e9ecef;
            transform: translateY(-3px);
        }

        .tooltip-inner {
            max-width: 300px;
        }

        .shortcut-section {
            background-color: rgba(13, 110, 253, 0.05);
            border-radius: 1rem;
            padding: 2rem 0;
        }

        .step-card {
            position: relative;
            padding-left: 3rem;
        }

        .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 2.5rem;
            height: 2.5rem;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <!-- 主視覺區塊 -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <h1 class="fw-bold display-4 mb-3">健行科技大學電算中心管理系統</h1>
                <p class="lead mb-4">專為電算中心人員設計的綜合管理平台，提供教室狀態監控、硬碟更換記錄及課表管理等功能。</p>
            </div>
        </div>
    </section>

    <!-- 主要功能介紹 -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 animate-fade-up">
                <h2 class="fw-bold section-header">系統主要功能</h2>
                <p class="text-muted">專為電算中心設計的完整功能套件，簡化日常工作流程</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card animate-fade-up">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-display"></i>
                            </div>
                            <h4>教室使用狀態</h4>
                            <p class="text-muted">即時監控各教室使用狀態，輕鬆查詢空閒教室，提高資源利用效率。</p>
                            @auth
                                <a href="{{ route('classroom.status') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-right-circle me-1"></i>立即查看
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card feature-card animate-fade-up">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-hdd"></i>
                            </div>
                            <h4>硬碟更換管理</h4>
                            <p class="text-muted">完整記錄硬碟更換歷程，追蹤設備維護情況，確保系統穩定運行。</p>
                            @auth
                                <a href="{{ route('disk-replacement.index') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-right-circle me-1"></i>查看記錄
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card feature-card animate-fade-up">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <h4>課表同步更新</h4>
                            <p class="text-muted">自動同步最新課表資料，確保教室使用排程準確無誤。</p>
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('classroom.refresh.form') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-arrow-right-circle me-1"></i>更新課表
                                    </a>
                                @else
                                    <button class="btn btn-secondary mt-2" disabled>
                                        <i class="bi bi-lock me-1"></i>需管理員權限
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 使用流程指南 -->
    <section class="py-5 shortcut-section">
        <div class="container">
            <div class="text-center mb-5 animate-fade-up">
                <h2 class="fw-bold section-header">常用操作指南</h2>
                <p class="text-muted">快速了解系統功能的操作方式，提升工作效率</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6 animate-fade-up">
                    <h4 class="mb-4"><i class="bi bi-1-circle-fill me-2 text-primary"></i>教室狀態查詢</h4>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">1</div>
                            <h5>選擇學院</h5>
                            <p class="mb-0 text-muted">在教室狀態頁面頂部，選擇您想要查看的學院大樓。</p>
                        </div>
                    </div>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">2</div>
                            <h5>查看教室狀態</h5>
                            <p class="mb-0 text-muted">系統將顯示該學院所有教室，綠色表示空閒，紅色表示使用中。</p>
                        </div>
                    </div>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">3</div>
                            <h5>硬碟更換記錄</h5>
                            <p class="mb-0 text-muted">點擊教室卡片，可直接進行硬碟更換記錄的新增。</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 animate-fade-up">
                    <h4 class="mb-4"><i class="bi bi-2-circle-fill me-2 text-primary"></i>硬碟記錄管理</h4>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">1</div>
                            <h5>查詢過濾記錄</h5>
                            <p class="mb-0 text-muted">使用過濾選項卡，可依據學期、建築物、教室代碼和日期等條件篩選記錄。</p>
                        </div>
                    </div>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">2</div>
                            <h5>新增更換記錄</h5>
                            <p class="mb-0 text-muted">在教室狀態頁面選擇教室後，填寫硬碟狀況資訊，即可完成記錄。</p>
                        </div>
                    </div>

                    <div class="card mb-3 step-card">
                        <div class="card-body">
                            <div class="step-number">3</div>
                            <h5>檢視教室問題</h5>
                            <p class="mb-0 text-muted">點擊記錄列表中的「問題查看」按鈕，可查看完整的問題描述與處理情況。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var fadeElements = $('.animate-fade-up');

            var observer = new IntersectionObserver(function (entries) {
                $.each(entries, function (i, entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('show');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            fadeElements.each(function () {
                observer.observe(this);
            });

            $('[data-bs-toggle="tooltip"]').tooltip();

            function animateValue(id, start, end, duration) {
                var obj = $('#' + id);
                if (obj.length === 0) return;

                var startTimestamp = null;
                var step = function (timestamp) {
                    if (!startTimestamp) startTimestamp = timestamp;
                    var progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    var value = Math.floor(progress * (end - start) + start);

                    if (id === 'stat-uptime') {
                        obj.html(value.toFixed(1) + '%');
                    } else if (value > 99) {
                        obj.html(value + '+');
                    } else {
                        obj.html(value);
                    }

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            var statObserver = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    setTimeout(function () {
                        animateValue('stat-buildings', 0, 5, 1000);
                        animateValue('stat-classrooms', 0, 40, 1000);
                        animateValue('stat-computers', 0, 800, 1500);
                        animateValue('stat-uptime', 0, 99.9, 1500);
                    }, 300);
                    statObserver.disconnect();
                }
            });

            var statsSection = $('.stat-card');
            if (statsSection.length > 0) {
                statObserver.observe(statsSection[0]);
            }
        });
    </script>
@endpush