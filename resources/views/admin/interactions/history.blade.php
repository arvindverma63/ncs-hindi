<x-app-layout title="1 Day Interaction History | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @endpush

    <style>
        .timeline-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            background: #fff;
            border: 1px solid #f3f3f5;
        }
    </style>

    <div class="py-4">
        <div class="container-fluid">
            {{-- Breadcrumb/Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-black text-dark font-brand uppercase tracking-tight m-0">1 Day Interaction History</h2>
                    <p class="text-muted mt-1 mb-0 fs-14">All guest and user views, downloads, and likes recorded in the last 24 hours.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1 fw-bold fs-11">
                    Back to Dashboard
                </a>
            </div>

            <div class="card timeline-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-11 text-uppercase font-black text-muted">
                            <tr>
                                <th class="ps-3">User</th>
                                <th>IP Address</th>
                                <th>Action</th>
                                <th>Music Stem Info</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($interactions as $interaction)
                                @php
                                    $userLabel = $interaction->user->name ?? 'Guest User';
                                    $songTitle = $interaction->music->title ?? 'Unknown Track';

                                    // Set style and icon by interaction type
                                    $iconType = 'mdi:eye-outline';
                                    $iconColor = '#0dcaf0'; // Cyan
                                    $actionText = 'View';
                                    $actionClass = 'bg-info-subtle text-info border-info-subtle';

                                    if ($interaction->type === 'download') {
                                        $iconType = 'mdi:download-outline';
                                        $iconColor = '#198754'; // Green
                                        $actionText = 'Download';
                                        $actionClass = 'bg-success-subtle text-success border-success-subtle';
                                    } elseif ($interaction->type === 'like') {
                                        $iconType = 'mdi:heart-outline';
                                        $iconColor = '#dc3545'; // Red
                                        $actionText = 'Like';
                                        $actionClass = 'bg-danger-subtle text-danger border-danger-subtle';
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        @if($interaction->user)
                                            <a href="{{ route('admin.users.show', $interaction->user->id) }}" class="text-dark hover-underline">
                                                {{ $userLabel }}
                                            </a>
                                        @else
                                            <span class="text-secondary">{{ $userLabel }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="fs-12">{{ $interaction->ip_address ?: 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <span class="badge {{ $actionClass }} border px-2.5 py-1 fw-bold fs-11">
                                            <iconify-icon icon="{{ $iconType }}" class="align-bottom me-1"></iconify-icon>
                                            {{ $actionText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($interaction->music)
                                            <div class="d-flex align-items-center gap-2">
                                                @if($interaction->music->featured_image)
                                                    <img src="{{ $interaction->music->featured_image }}" alt="" style="width: 32px; height: 32px; object-fit: cover; border-radius: 0.25rem;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark fs-13">{{ $songTitle }}</div>
                                                    <div class="text-muted fs-11">{{ $interaction->music->artist_name ?: 'NCS Artist' }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted fs-13">{{ $songTitle }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $interaction->created_at ? \Carbon\Carbon::parse($interaction->created_at)->format('M d, Y H:i:s') . ' (' . \Carbon\Carbon::parse($interaction->created_at)->diffForHumans() . ')' : 'Recently' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <iconify-icon icon="mdi:clipboard-text-outline" class="fs-1 mb-2 opacity-25"></iconify-icon>
                                        <p class="fs-13 mb-0">No interactions recorded in the last 24 hours.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $interactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
