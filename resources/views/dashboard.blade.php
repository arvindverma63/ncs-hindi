<x-app-layout title="Dashboard | NCS Hindi Admin">


    <style>
        .stat-card {
            border: none;
            border-radius: 1.25rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            background: #fff;
            overflow: hidden;
            position: relative;
            border: 1px solid #f3f3f5;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .pulse-badge {
            background: rgba(220, 53, 69, 0.08);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.15);
            padding: 0.5rem 1.25rem;
            border-radius: 2rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(220, 53, 69, 0.08);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #dc3545;
            border-radius: 50%;
            animation: pulse-animation 1.6s infinite;
        }

        @keyframes pulse-animation {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.6);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(220, 53, 69, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .rank-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
        }

        .rank-1 { background: rgba(255, 193, 7, 0.15); color: #ffc107; }
        .rank-2 { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
        .rank-3 { background: rgba(184, 115, 51, 0.15); color: #b87333; }
        .rank-other { background: #f8f9fa; color: #6c757d; }

        .activity-timeline {
            position: relative;
            padding-left: 1.5rem;
            border-left: 2px solid #f1f1f4;
        }

        .activity-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .activity-item:last-child {
            padding-bottom: 0;
        }

        .activity-icon {
            position: absolute;
            left: calc(-1.5rem - 11px);
            top: 2px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .tab-btn {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 0.65rem 1.15rem;
            border-radius: 0.5rem;
            border: none;
            background: transparent;
            color: #6c757d;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            color: brown;
            background: rgba(165, 42, 42, 0.03);
        }

        .tab-btn.active {
            background: rgba(165, 42, 42, 0.08) !important; /* Brown tint matching platform */
            color: brown !important;
        }

        .dashboard-banner {
            background: linear-gradient(135deg, #4b2c20 0%, #1f0f0a 100%);
            border-radius: 1.25rem;
            color: #fff;
            padding: 2.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .dashboard-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            pointer-events: none;
        }

        .music-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #f0f0f2;
        }

        .table-responsive {
            border-radius: 0.75rem;
        }

        .badge-trend-up {
            background-color: rgba(25, 135, 84, 0.08) !important;
            color: #198754 !important;
            border: 1px solid rgba(25, 135, 84, 0.15);
            border-radius: 2rem;
            font-size: 0.7rem;
            padding: 0.25rem 0.6rem;
            font-weight: 700;
        }

        .badge-trend-down {
            background-color: rgba(220, 53, 69, 0.08) !important;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.15);
            border-radius: 2rem;
            font-size: 0.7rem;
            padding: 0.25rem 0.6rem;
            font-weight: 700;
        }

        .bg-white-10 {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .border-white-20 {
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        /* Overlay Skeleton loading animation */
        .chart-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: inherit;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .chart-loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
    </style>

    <div class="py-4">
        <div class="container-fluid">
            {{-- Premium Header Welcome Banner --}}
            <div class="dashboard-banner d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div>
                    <h2 class="fw-black m-0 text-white font-brand uppercase tracking-tight">Music Desk</h2>
                    <p class="text-white-50 mt-1 mb-0 fs-14">Real-time engagement analysis, content rankings, and platform telemetry.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <div class="pulse-badge bg-white-10 text-white border-white-20">
                        <span class="pulse-dot"></span>
                        <span>{{ number_format($activeViews) }} LIVE VISITORS</span>
                    </div>
                    <span class="badge bg-white-10 text-white border-white-20 p-2.5 rounded-3 d-inline-flex align-items-center gap-1.5 fs-12 fw-bold">
                        <iconify-icon icon="mdi:account-multiple"></iconify-icon>
                        {{ number_format($totalUsers) }} USERS
                    </span>
                    <a href="{{ route('admin.reports.index') }}" class="badge bg-white-10 text-white border-white-20 p-2.5 rounded-3 d-inline-flex align-items-center gap-1.5 fs-12 fw-bold text-decoration-none hover-scale">
                        <iconify-icon icon="mdi:bug"></iconify-icon>
                        {{ $openBugs }} BUGS
                    </a>
                </div>
            </div>

            {{-- Timeframe Filter Bar --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 p-3 bg-white rounded-4 shadow-sm border border-light">
                <h5 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:chart-timeline-variant" class="text-secondary fs-4"></iconify-icon> 
                    Music Telemetry Scope
                </h5>
                <div class="bg-light p-1 rounded-3 d-inline-flex">
                    <button type="button" class="tab-btn dashboard-filter-btn" data-timeframe="today">Today</button>
                    <button type="button" class="tab-btn dashboard-filter-btn active" data-timeframe="weekly">Weekly View</button>
                    <button type="button" class="tab-btn dashboard-filter-btn" data-timeframe="monthly">Monthly View</button>
                </div>
            </div>

            {{-- Metric Stats Cards --}}
            <div class="row g-4 mb-4">
                {{-- Card 1: Views --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Views</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0" id="views-count">0</h3>
                            </div>
                            <div class="bg-primary-subtle text-primary rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:eye" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted d-flex align-items-center gap-1.5" id="views-trend-wrapper">
                            <span class="badge" id="views-trend-badge">
                                <iconify-icon icon="mdi:trending-up"></iconify-icon> <span id="views-trend-val">0%</span>
                            </span>
                            <span class="text-secondary">vs prev period</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Downloads --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Downloads</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0" id="downloads-count">0</h3>
                            </div>
                            <div class="bg-success-subtle text-success rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:download" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted d-flex align-items-center gap-1.5" id="downloads-trend-wrapper">
                            <span class="badge" id="downloads-trend-badge">
                                <iconify-icon icon="mdi:trending-up"></iconify-icon> <span id="downloads-trend-val">0%</span>
                            </span>
                            <span class="text-secondary">vs prev period</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Likes --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Likes</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0" id="likes-count">0</h3>
                            </div>
                            <div class="bg-danger-subtle text-danger rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:heart" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted d-flex align-items-center gap-1.5" id="likes-trend-wrapper">
                            <span class="badge" id="likes-trend-badge">
                                <iconify-icon icon="mdi:trending-up"></iconify-icon> <span id="likes-trend-val">0%</span>
                            </span>
                            <span class="text-secondary">vs prev period</span>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Unique Listeners --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Unique Listeners</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0" id="unique-count">0</h3>
                            </div>
                            <div class="bg-info-subtle text-info rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:account-music" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted d-flex align-items-center gap-1.5" id="unique-trend-wrapper">
                            <span class="badge" id="unique-trend-badge">
                                <iconify-icon icon="mdi:trending-up"></iconify-icon> <span id="unique-trend-val">0%</span>
                            </span>
                            <span class="text-secondary">vs prev period</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Analytics Charts Grid --}}
            <div class="row g-4 mb-4">
                {{-- Chart 1: Activity Trend --}}
                <div class="col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100 position-relative" style="border: 1px solid #f3f3f5 !important; min-height: 400px;">
                        <div class="chart-loading-overlay" id="trend-chart-loader">
                            <div class="spinner-border text-brown" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h4 class="fw-bold text-dark mb-0 font-brand uppercase tracking-tight">Engagement Telemetry</h4>
                            <p class="text-muted fs-12 mb-0">Timeline visualization of Views, Downloads, and Likes.</p>
                        </div>
                        <div style="height: 300px; position: relative; width: 100%;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 2: Category Distribution --}}
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100 position-relative" style="border: 1px solid #f3f3f5 !important; min-height: 400px;">
                        <div class="chart-loading-overlay" id="category-chart-loader">
                            <div class="spinner-border text-brown" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h4 class="fw-bold text-dark mb-0 font-brand uppercase tracking-tight">Category Breakdown</h4>
                            <p class="text-muted fs-12 mb-0">Distribution of user actions across genres/categories.</p>
                        </div>
                        <div style="height: 280px; position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                {{-- Chart 3: Top Music Stems Comparison --}}
                <div class="col-12">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white position-relative" style="border: 1px solid #f3f3f5 !important;">
                        <div class="chart-loading-overlay" id="top-music-chart-loader">
                            <div class="spinner-border text-brown" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h4 class="fw-bold text-dark mb-0 font-brand uppercase tracking-tight">Top Music Performance</h4>
                            <p class="text-muted fs-12 mb-0">Side-by-side metric comparison of the top performing music stems in this period.</p>
                        </div>
                        <div style="height: 350px; position: relative; width: 100%;">
                            <canvas id="topMusicChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Dashboard Layout --}}
            <div class="row g-4">
                {{-- Left Column: Dynamic Leaderboards --}}
                <div class="col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white position-relative">
                        <div class="chart-loading-overlay" id="tables-loader">
                            <div class="spinner-border text-brown" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-0 font-brand uppercase tracking-tight">Engagement Rankings</h4>
                                <p class="text-muted fs-12 mb-0">Leaderboards tracking the most active music stems in this period.</p>
                            </div>

                            {{-- Tab Headers --}}
                            <div class="bg-light p-1 rounded-3 d-inline-flex">
                                <ul class="nav nav-pills" id="rankingsTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="tab-btn active" id="views-tab" data-bs-toggle="pill" data-bs-target="#views-pane" type="button" role="tab" aria-controls="views-pane" aria-selected="true">
                                            <iconify-icon icon="mdi:eye" class="align-text-bottom me-1"></iconify-icon> Top Views
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="tab-btn" id="likes-tab" data-bs-toggle="pill" data-bs-target="#likes-pane" type="button" role="tab" aria-controls="likes-pane" aria-selected="false">
                                            <iconify-icon icon="mdi:heart" class="align-text-bottom me-1"></iconify-icon> Top Likes
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="tab-btn" id="downloads-tab" data-bs-toggle="pill" data-bs-target="#downloads-pane" type="button" role="tab" aria-controls="downloads-pane" aria-selected="false">
                                            <iconify-icon icon="mdi:download" class="align-text-bottom me-1"></iconify-icon> Top Downloads
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Tab Panes --}}
                        <div class="tab-content" id="rankingsTabContent">
                            {{-- Pane 1: Top Views --}}
                            <div class="tab-pane fade show active" id="views-pane" role="tabpanel" aria-labelledby="views-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light fs-11 text-uppercase font-black text-muted">
                                            <tr>
                                                <th class="ps-3" style="width: 70px;">Rank</th>
                                                <th>Music Stem Info</th>
                                                <th>Category</th>
                                                <th>Metric count</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-views-body">
                                            {{-- Rendered dynamically --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Pane 2: Top Likes --}}
                            <div class="tab-pane fade" id="likes-pane" role="tabpanel" aria-labelledby="likes-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light fs-11 text-uppercase font-black text-muted">
                                            <tr>
                                                <th class="ps-3" style="width: 70px;">Rank</th>
                                                <th>Music Stem Info</th>
                                                <th>Category</th>
                                                <th>Metric count</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-likes-body">
                                            {{-- Rendered dynamically --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Pane 3: Top Downloads --}}
                            <div class="tab-pane fade" id="downloads-pane" role="tabpanel" aria-labelledby="downloads-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light fs-11 text-uppercase font-black text-muted">
                                            <tr>
                                                <th class="ps-3" style="width: 70px;">Rank</th>
                                                <th>Music Stem Info</th>
                                                <th>Category</th>
                                                <th>Metric count</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-downloads-body">
                                            {{-- Rendered dynamically --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Live Audit Activity Feed --}}
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-0 font-brand uppercase tracking-tight">Interaction Feed</h4>
                                <p class="text-muted fs-12 mb-0">Recent events log.</p>
                            </div>
                            <a href="{{ route('admin.interactions.history') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11 text-nowrap">
                                View 1 Day History
                            </a>
                        </div>

                        <div class="activity-timeline">
                            @forelse($recentInteractions as $interaction)
                                @php
                                    $userLabel = $interaction->user->name ?? 'Guest User';
                                    $songTitle = $interaction->music->title ?? 'Unknown Track';

                                    // Set style and icon by interaction type
                                    $iconType = 'mdi:eye-outline';
                                    $iconColor = '#0dcaf0'; // Cyan
                                    $iconBg = 'rgba(13, 202, 240, 0.1)';
                                    $actionText = 'viewed';

                                    if ($interaction->type === 'download') {
                                        $iconType = 'mdi:download-outline';
                                        $iconColor = '#198754'; // Green
                                        $iconBg = 'rgba(25, 135, 84, 0.1)';
                                        $actionText = 'downloaded';
                                    } elseif ($interaction->type === 'like') {
                                        $iconType = 'mdi:heart-outline';
                                        $iconColor = '#dc3545'; // Red
                                        $iconBg = 'rgba(220, 53, 69, 0.1)';
                                        $actionText = 'liked';
                                    }
                                @endphp

                                <div class="activity-item">
                                    <div class="activity-icon" style="background-color: {{ $iconBg }}; color: {{ $iconColor }}; border-color: {{ $iconColor }};">
                                        <iconify-icon icon="{{ $iconType }}" width="12" height="12"></iconify-icon>
                                    </div>
                                    <div class="ms-1">
                                        <p class="fs-13 mb-1 text-dark">
                                            <strong class="text-secondary">{{ $userLabel }}</strong>
                                            <span class="text-muted">{{ $actionText }}</span>
                                            <strong class="text-dark">{{ $songTitle }}</strong>
                                        </p>
                                        <div class="fs-11 text-muted d-flex align-items-center gap-1">
                                            <iconify-icon icon="mdi:clock-outline"></iconify-icon>
                                            {{ $interaction->created_at ? \Carbon\Carbon::parse($interaction->created_at)->diffForHumans() : 'Recently' }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <iconify-icon icon="mdi:clipboard-text-outline" class="fs-1 mb-2 opacity-25"></iconify-icon>
                                    <p class="fs-12 mb-0">No logged interaction telemetry detected yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Chart instances pointers
            let trendChart = null;
            let categoryChart = null;
            let topMusicChart = null;

            // Load analytics data for a specific timeframe
            function loadDashboardData(timeframe) {
                // Show loading states
                $('.chart-loading-overlay').addClass('active');

                $.ajax({
                    url: "{{ route('admin.dashboard.analytics') }}",
                    method: 'GET',
                    data: { timeframe: timeframe },
                    success: function(response) {
                        updateMetrics(response.metrics);
                        renderTrendChart(response.trend_chart, timeframe);
                        renderCategoryChart(response.category_chart);
                        renderTopMusicChart(response.top_music_chart);
                        updateLeaderboardTables(response.tables);
                    },
                    error: function(xhr) {
                        console.error('Failed to retrieve analytics data:', xhr);
                        toastr.error('Failed to load telemetry statistics.');
                    },
                    complete: function() {
                        // Hide loading states
                        $('.chart-loading-overlay').removeClass('active');
                    }
                });
            }

            // Update KPI metric counter numbers & trend styles
            function updateMetrics(metrics) {
                // Animate count-ups or just swap HTML
                animateValue("views-count", metrics.views.count);
                animateValue("downloads-count", metrics.downloads.count);
                animateValue("likes-count", metrics.likes.count);
                animateValue("unique-count", metrics.unique.count);

                // Update trend badges
                updateTrendIndicator("views-trend", metrics.views);
                updateTrendIndicator("downloads-trend", metrics.downloads);
                updateTrendIndicator("likes-trend", metrics.likes);
                updateTrendIndicator("unique-trend", metrics.unique);
            }

            function animateValue(id, targetVal) {
                const el = document.getElementById(id);
                if (!el) return;
                const start = parseInt(el.innerText.replace(/,/g, '')) || 0;
                const duration = 600;
                let startTimestamp = null;
                
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    const currentVal = Math.floor(progress * (targetVal - start) + start);
                    el.innerText = new Intl.NumberFormat().format(currentVal);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        el.innerText = new Intl.NumberFormat().format(targetVal);
                    }
                };
                window.requestAnimationFrame(step);
            }

            function updateTrendIndicator(prefix, data) {
                const $badge = $('#' + prefix + '-badge');
                const $val = $('#' + prefix + '-val');
                
                // Clear classes
                $badge.removeClass('badge-trend-up badge-trend-down bg-light text-muted');
                
                let sign = data.change >= 0 ? '+' : '';
                $val.text(sign + data.change + '%');
                
                if (data.change > 0) {
                    $badge.addClass('badge-trend-up');
                    $badge.find('iconify-icon').attr('icon', 'mdi:trending-up');
                } else if (data.change < 0) {
                    $badge.addClass('badge-trend-down');
                    $badge.find('iconify-icon').attr('icon', 'mdi:trending-down');
                } else {
                    $badge.addClass('bg-light text-muted border px-2 py-0.5 rounded-pill');
                    $badge.find('iconify-icon').attr('icon', 'mdi:trending-neutral');
                }
            }

            // Chart 1: Activity Trend Area Line Chart
            function renderTrendChart(data, timeframe) {
                const ctx = document.getElementById('trendChart').getContext('2d');
                if (trendChart) trendChart.destroy();

                // Define gradients
                const viewGrad = ctx.createLinearGradient(0, 0, 0, 300);
                viewGrad.addColorStop(0, 'rgba(13, 202, 240, 0.4)');
                viewGrad.addColorStop(1, 'rgba(13, 202, 240, 0.0)');

                const dlGrad = ctx.createLinearGradient(0, 0, 0, 300);
                dlGrad.addColorStop(0, 'rgba(25, 135, 84, 0.4)');
                dlGrad.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

                const likeGrad = ctx.createLinearGradient(0, 0, 0, 300);
                likeGrad.addColorStop(0, 'rgba(220, 53, 69, 0.4)');
                likeGrad.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

                trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Views',
                                data: data.views,
                                backgroundColor: viewGrad,
                                borderColor: '#0dcaf0',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Downloads',
                                data: data.downloads,
                                backgroundColor: dlGrad,
                                borderColor: '#198754',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Likes',
                                data: data.likes,
                                backgroundColor: likeGrad,
                                borderColor: '#dc3545',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: { weight: '700', family: 'system-ui' },
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: '#111',
                                padding: 12,
                                cornerRadius: 8,
                                bodySpacing: 6,
                                titleFont: { weight: 'bold', family: 'system-ui' }
                            }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f3f5' },
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            // Chart 2: Category Distribution Doughnut
            function renderCategoryChart(data) {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                if (categoryChart) categoryChart.destroy();

                categoryChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: [
                                '#a52a2a', // Brown (Brand primary)
                                '#0dcaf0', // Cyan
                                '#198754', // Green
                                '#ffc107', // Gold
                                '#6f42c1', // Purple
                                '#fd7e14'  // Orange
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { weight: '600', family: 'system-ui', size: 10 },
                                    padding: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let val = context.raw;
                                        let pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                        return ` ${context.label}: ${val} (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            // Chart 3: Top Music Stems Side-by-Side Horizontal Bar Chart
            function renderTopMusicChart(data) {
                const ctx = document.getElementById('topMusicChart').getContext('2d');
                if (topMusicChart) topMusicChart.destroy();

                topMusicChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Views',
                                data: data.views,
                                backgroundColor: 'rgba(13, 202, 240, 0.85)',
                                borderColor: '#0dcaf0',
                                borderWidth: 1
                            },
                            {
                                label: 'Downloads',
                                data: data.downloads,
                                backgroundColor: 'rgba(25, 135, 84, 0.85)',
                                borderColor: '#198754',
                                borderWidth: 1
                            },
                            {
                                label: 'Likes',
                                data: data.likes,
                                backgroundColor: 'rgba(220, 53, 69, 0.85)',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y', // Makes it horizontal!
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: { weight: '700', family: 'system-ui' }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: '#f3f3f5' },
                                ticks: { precision: 0 }
                            },
                            y: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Helper to generate dynamic table rows
            function updateLeaderboardTables(tables) {
                renderTableRows($('#table-views-body'), tables.views, 'views', 'views');
                renderTableRows($('#table-likes-body'), tables.likes, 'likes', 'likes');
                renderTableRows($('#table-downloads-body'), tables.downloads, 'downloads', 'downloads');
            }

            function renderTableRows($tbody, data, metricName, unitText) {
                $tbody.empty();
                if (!data || data.length === 0) {
                    $tbody.html(`<tr><td colspan="5" class="text-center py-5 text-muted">No interactions detected in this period.</td></tr>`);
                    return;
                }
                
                data.forEach((song, index) => {
                    let imageHtml = song.featured_image 
                        ? `<img src="${song.featured_image}" alt="" class="music-thumb">`
                        : `<div class="music-thumb bg-light border d-flex align-items-center justify-content-center text-muted"><iconify-icon icon="mdi:music"></iconify-icon></div>`;
                        
                    let rankClass = (index + 1 <= 3) ? `rank-${index + 1}` : 'rank-other';
                    let countColorClass = metricName === 'likes' ? 'text-danger' : (metricName === 'downloads' ? 'text-success' : 'text-dark');
                    
                    let rowHtml = `
                        <tr>
                            <td class="ps-3">
                                <span class="rank-number ${rankClass}">#${index + 1}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    ${imageHtml}
                                    <div>
                                        <div class="fw-bold text-dark fs-14">${song.title}</div>
                                        <div class="text-muted fs-12">${song.artist_name}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border px-2.5 py-1 fw-normal">
                                    ${song.category_name}
                                </span>
                            </td>
                            <td>
                                <span class="fw-black ${countColorClass} fs-14">${new Intl.NumberFormat().format(song.count)}</span>
                                <span class="text-muted fs-11 ms-1">${unitText}</span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="/admin/music/${song.id}/edit" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    `;
                    $tbody.append(rowHtml);
                });
            }

            // Click listener for timeframe toggle
            $('.dashboard-filter-btn').on('click', function() {
                $('.dashboard-filter-btn').removeClass('active');
                $(this).addClass('active');
                
                const timeframe = $(this).data('timeframe');
                loadDashboardData(timeframe);
            });

            // Initial auto-trigger to load Weekly Analytics on boot
            loadDashboardData('weekly');
        });
    </script>
    @endpush
</x-app-layout>
