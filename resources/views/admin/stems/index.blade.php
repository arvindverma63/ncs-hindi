<x-app-layout title="Stem Management | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @endpush

    <style>
        .table-container {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #f0f0f0;
        }

        .table thead th {
            background: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6c757d;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }

        .search-input {
            border-radius: 0.5rem 0 0 0.5rem !important;
            border-right: none;
        }

        .search-btn {
            border-radius: 0 0.5rem 0.5rem 0 !important;
        }

        .filter-select {
            border-radius: 0.5rem;
            min-width: 160px;
        }

        .stem-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }
    </style>

    <div class="py-4">
        <div class="container-fluid">
            {{-- Header & Controls --}}
            <div class="row align-items-center mb-4 g-3">
                <div class="col-md-4">
                    <h3 class="fw-bold text-dark mb-0">Official Stems Vault</h3>
                    <p class="text-muted small mb-0">Manage {{ $stems->total() }} studio assets</p>
                </div>

                <div class="col-md-5">
                    <form action="{{ route('admin.stems.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <input type="text" name="search"
                                class="form-control search-input border-light bg-white shadow-sm"
                                placeholder="Search by title or artist..." value="{{ request('search') }}">
                            <button class="btn btn-primary search-btn px-3 shadow-sm" type="submit">
                                <iconify-icon icon="mdi:magnify"></iconify-icon>
                            </button>
                        </div>
                        <select name="category" class="form-select filter-select border-light bg-white shadow-sm"
                            onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3 text-md-end">
                    <a href="{{ route('admin.stems.create') }}"
                        class="btn btn-primary px-4 rounded-3 shadow-sm fw-bold">
                        <iconify-icon icon="mdi:plus-circle" class="me-1"></iconify-icon> NEW RELEASE
                    </a>
                </div>
            </div>

            {{-- Table View --}}
            <div class="table-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Music Info</th>
                                <th>Category</th>
                                <th>BPM / Key</th>
                                <th>Engagement</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stems as $stem)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($stem->featured_image)
                                                <img src="{{ $stem->featured_image }}" alt="{{ $stem->title }}"
                                                    class="stem-img border me-3">
                                            @else
                                                <div
                                                    class="stem-img bg-light border d-flex align-items-center justify-content-center me-3 text-muted">
                                                    <iconify-icon icon="mdi:music-note"></iconify-icon>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $stem->title }}</div>
                                                <div class="text-muted small">
                                                    {{ $stem->artist_name ?: 'Unknown Artist' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                            {{ $stem->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $stem->bpm ?? '--' }} <span
                                                class="text-muted fw-normal">BPM</span></div>
                                        <div class="small text-primary fw-bold">{{ $stem->music_key ?? '--' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            <span class="small text-muted" title="Downloads"><iconify-icon
                                                    icon="mdi:download" class="text-info"></iconify-icon>
                                                {{ $stem->download_count }}</span>
                                            <span class="small text-muted" title="Likes"><iconify-icon icon="mdi:heart"
                                                    class="text-danger"></iconify-icon> {{ $stem->like_count }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($stem->is_public)
                                            <span
                                                class="text-success small fw-bold d-flex align-items-center"><iconify-icon
                                                    icon="mdi:check-circle" class="me-1"></iconify-icon> LIVE</span>
                                        @else
                                            <span
                                                class="text-warning small fw-bold d-flex align-items-center"><iconify-icon
                                                    icon="mdi:clock" class="me-1"></iconify-icon> DRAFT</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.stems.edit', $stem->id) }}"
                                                class="action-btn text-primary border" title="Edit">
                                                <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.stems.destroy', $stem->id) }}" method="POST"
                                                onsubmit="return confirm('Delete permanently?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-btn text-danger border"
                                                    title="Delete">
                                                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <iconify-icon icon="mdi:database-search" width="48"
                                            class="text-muted opacity-25 mb-2"></iconify-icon>
                                        <p class="text-muted">No matching assets found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $stems->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
