<x-app-layout title="Dashboard | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @endpush

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
    </style>

    <div class="py-4">
        <div class="container-fluid">
            {{-- Premium Header Welcome Banner --}}
            <div class="dashboard-banner d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div>
                    <h2 class="fw-black m-0 text-white font-brand uppercase tracking-tight">Music Desk</h2>
                    <p class="text-white-50 mt-1 mb-0 fs-14">Real-time engagement analysis, content rankings, and platform telemetry.</p>
                </div>
                <div>
                    <div class="pulse-badge">
                        <span class="pulse-dot"></span>
                        <span>{{ number_format($activeViews) }} LIVE VISITORS ON WEBSITE</span>
                    </div>
                </div>
            </div>

            {{-- Metric Stats Cards --}}
            <div class="row g-4 mb-4">
                {{-- Card 1: Total Views --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Total Music Views</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($totalViews) }}</h3>
                            </div>
                            <div class="bg-primary-subtle text-primary rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:eye" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted">
                            <span class="text-success fw-bold"><iconify-icon icon="mdi:trending-up" class="align-bottom"></iconify-icon> Live</span>
                            <span>Engagement aggregation</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Downloads --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Total Downloads</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($totalDownloads) }}</h3>
                            </div>
                            <div class="bg-success-subtle text-success rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:download" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted">
                            <span class="text-success fw-bold"><iconify-icon icon="mdi:trending-up" class="align-bottom"></iconify-icon> Real-time</span>
                            <span>Official vault releases</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Likes --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Total Likes</span>
                                <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($totalLikes) }}</h3>
                            </div>
                            <div class="bg-danger-subtle text-danger rounded-3 p-2.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:heart" width="24" height="24"></iconify-icon>
                            </div>
                        </div>
                        <div class="fs-12 text-muted">
                            <span class="text-success fw-bold"><iconify-icon icon="mdi:trending-up" class="align-bottom"></iconify-icon> Active</span>
                            <span>Community reviews</span>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Community size & Bugs (Grouped 2-in-1 for perfect fit) --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="text-muted text-uppercase font-black tracking-wider fs-11">Platform Core</span>
                            </div>
                            <div class="bg-info-subtle text-info rounded-3 p-1.5 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="mdi:server" width="18" height="18"></iconify-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom border-light">
                            <span class="fs-13 text-dark fw-medium d-flex align-items-center gap-1.5">
                                <iconify-icon icon="mdi:account-multiple" class="text-muted"></iconify-icon> Website Users
                            </span>
                            <span class="badge bg-light text-dark border fw-bold fs-11">{{ number_format($totalUsers) }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-1.5">
                            <span class="fs-13 text-dark fw-medium d-flex align-items-center gap-1.5">
                                <iconify-icon icon="mdi:bug" class="text-muted"></iconify-icon> Pending Bug Reports
                            </span>
                            @if($openBugs > 0)
                                <a href="{{ route('admin.reports.index') }}" class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold fs-11 hover-scale">{{ $openBugs }} unresolved</a>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal fs-11">0 pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Dashboard Layout --}}
            <div class="row g-4">
                {{-- Left Column: Interactive Content Charts --}}
                <div class="col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-0">Engagement Rankings</h4>
                                <p class="text-muted fs-12 mb-0">Leaderboards tracking the most viewed, liked, and downloaded music stems.</p>
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
                                        <tbody>
                                            @forelse($topViewedSongs as $index => $song)
                                                <tr>
                                                    <td class="ps-3">
                                                        <span class="rank-number rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">
                                                            #{{ $index + 1 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($song->featured_image)
                                                                <img src="{{ $song->featured_image }}" alt="" class="music-thumb">
                                                            @else
                                                                <div class="music-thumb bg-light border d-flex align-items-center justify-content-center text-muted">
                                                                    <iconify-icon icon="mdi:music"></iconify-icon>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold text-dark fs-14">{{ $song->title }}</div>
                                                                <div class="text-muted fs-12">{{ $song->artist_name ?: 'NCS Artist' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-secondary border px-2.5 py-1 fw-normal">
                                                            {{ $song->category->name ?? 'Vault' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-black text-dark fs-14">{{ number_format($song->view_count) }}</span>
                                                        <span class="text-muted fs-11 ms-1">views</span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <a href="{{ route('admin.music.edit', $song->id) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11">
                                                            Manage
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No music tracks loaded in the vault yet.</td>
                                                </tr>
                                            @endforelse
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
                                        <tbody>
                                            @forelse($topLikedSongs as $index => $song)
                                                <tr>
                                                    <td class="ps-3">
                                                        <span class="rank-number rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">
                                                            #{{ $index + 1 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($song->featured_image)
                                                                <img src="{{ $song->featured_image }}" alt="" class="music-thumb">
                                                            @else
                                                                <div class="music-thumb bg-light border d-flex align-items-center justify-content-center text-muted">
                                                                    <iconify-icon icon="mdi:music"></iconify-icon>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold text-dark fs-14">{{ $song->title }}</div>
                                                                <div class="text-muted fs-12">{{ $song->artist_name ?: 'NCS Artist' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-secondary border px-2.5 py-1 fw-normal">
                                                            {{ $song->category->name ?? 'Vault' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-black text-danger fs-14">{{ number_format($song->like_count) }}</span>
                                                        <span class="text-muted fs-11 ms-1">likes</span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <a href="{{ route('admin.music.edit', $song->id) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11">
                                                            Manage
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No liked tracks on the website yet.</td>
                                                </tr>
                                            @endforelse
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
                                                <th>BPM & Key</th>
                                                <th>Metric count</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topDownloadedSongs as $index => $song)
                                                <tr>
                                                    <td class="ps-3">
                                                        <span class="rank-number rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">
                                                            #{{ $index + 1 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            @if($song->featured_image)
                                                                <img src="{{ $song->featured_image }}" alt="" class="music-thumb">
                                                            @else
                                                                <div class="music-thumb bg-light border d-flex align-items-center justify-content-center text-muted">
                                                                    <iconify-icon icon="mdi:music"></iconify-icon>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold text-dark fs-14">{{ $song->title }}</div>
                                                                <div class="text-muted fs-12">{{ $song->artist_name ?: 'NCS Artist' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fs-13 fw-bold text-dark">{{ $song->bpm ?? '--' }} BPM</div>
                                                        <div class="fs-11 text-primary fw-medium">{{ $song->music_key ?? '--' }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-black text-success fs-14">{{ number_format($song->download_count) }}</span>
                                                        <span class="text-muted fs-11 ms-1">downloads</span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <a href="{{ route('admin.music.edit', $song->id) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11">
                                                            Manage
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No downloaded stems yet.</td>
                                                </tr>
                                            @endforelse
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
                                <h4 class="fw-bold text-dark mb-0">Platform Interaction Feed</h4>
                                <p class="text-muted fs-12 mb-0">Chronological telemetry of guest and authenticated user operations.</p>
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
</x-app-layout>
