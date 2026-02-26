<x-app-layout title="Stem Management | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @endpush

    <div class="py-4">
        <div class="container-fluid">
            {{-- Header Section --}}
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold text-dark mb-1">Official Stems Vault</h3>
                    <p class="text-muted small mb-0">Manage high-fidelity studio assets and monitor downloads.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('admin.stems.create') }}" class="btn btn-primary px-4 shadow-sm rounded-3">
                        <iconify-icon icon="mdi:plus-thick" class="me-1"
                            style="vertical-align: middle;"></iconify-icon>
                        New Release
                    </a>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <iconify-icon icon="mdi:music-box-multiple" class="text-primary fs-3"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Stems</h6>
                                <h4 class="fw-bold mb-0">{{ $stems->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-info">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                                <iconify-icon icon="mdi:cloud-download" class="text-info fs-3"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Vault Downloads</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stems->sum('downloads_count')) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory Table --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="border-0 px-4 py-3">Asset Release</th>
                                <th class="border-0 py-3">Genre / Category</th>
                                <th class="border-0 py-3">Tech Specs</th>
                                <th class="border-0 py-3">Engagement</th>
                                <th class="border-0 py-3">Visibility</th>
                                <th class="border-0 px-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stems as $stem)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 position-relative">
                                                @if ($stem->featured_image)
                                                    <img src="{{ asset('storage/' . $stem->featured_image) }}"
                                                        class="rounded-3 shadow-sm" width="50" height="50"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary"
                                                        style="width: 50px; height: 50px;">
                                                        <iconify-icon icon="mdi:music-circle-outline"
                                                            fs-3></iconify-icon>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $stem->title }}</div>
                                                <div class="text-muted small">{{ $stem->file_size }} • <span
                                                        class="text-uppercase">{{ pathinfo($stem->file_name, PATHINFO_EXTENSION) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-light text-dark border border-secondary border-opacity-10 px-3 py-2 rounded-pill small">
                                            <iconify-icon icon="mdi:tag-outline" class="me-1"></iconify-icon>
                                            {{ $stem->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary small px-2 py-1">{{ $stem->bpm }}
                                                BPM</span>
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger small px-2 py-1">{{ $stem->music_key }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="small" title="Downloads">
                                                <iconify-icon icon="mdi:arrow-down-bold-circle-outline"
                                                    class="text-info"></iconify-icon>
                                                <span class="fw-bold">{{ $stem->downloads_count }}</span>
                                            </div>
                                            <div class="small" title="Likes">
                                                <iconify-icon icon="mdi:heart" class="text-danger"></iconify-icon>
                                                <span class="fw-bold">{{ $stem->likes_count }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($stem->is_public)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-bold">
                                                <iconify-icon icon="mdi:eye" class="me-1"></iconify-icon> PUBLIC
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 small fw-bold">
                                                <iconify-icon icon="mdi:eye-off" class="me-1"></iconify-icon> HIDDEN
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group shadow-sm rounded-3">
                                            <a href="{{ route('admin.stems.edit', $stem->id) }}"
                                                class="btn btn-white btn-sm px-3" title="Edit Release">
                                                <iconify-icon icon="mdi:pencil-outline"
                                                    class="text-secondary"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.stems.destroy', $stem->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Archive this studio asset?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-white btn-sm px-3 border-start text-danger"
                                                    title="Delete">
                                                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <iconify-icon icon="mdi:database-off-outline" width="48"
                                            class="d-block mx-auto mb-2 opacity-25"></iconify-icon>
                                        <p class="mb-0">No studio assets found in the vault.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                @if ($stems->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Showing {{ $stems->firstItem() }} to
                                {{ $stems->lastItem() }} of {{ $stems->total() }} assets</span>
                            {{ $stems->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .btn-white {
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .btn-white:hover {
            background: #f8f9fa;
        }

        .table> :not(caption)>*>* {
            border-bottom-width: 1px;
            border-color: #f1f1f1;
        }
    </style>
</x-app-layout>
