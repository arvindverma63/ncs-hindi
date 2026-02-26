<x-app-layout title="Stem Management | NCS Hindi Admin">
    <div class="content">
        <div class="container-fluid">
            {{-- Header Actions --}}
            <div class="row mb-4">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="font-weight-bold">Official Stems Vault</h4>
                        <p class="text-muted small">Manage official releases and monitor community engagement.</p>
                    </div>
                    <a href="{{ route('admin.stems.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Create New Release
                    </a>
                </div>
            </div>

            {{-- Stats Overview --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <h6 class="text-muted text-uppercase small font-weight-bold">Total Stems</h6>
                        <h3 class="mb-0">{{ $stems->total() }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 border-left border-info">
                        <h6 class="text-muted text-uppercase small font-weight-bold">Vault Downloads</h6>
                        <h3 class="mb-0 text-info">{{ number_format($stems->sum('interactions_count')) }}</h3>
                    </div>
                </div>
            </div>

            {{-- Stems Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4">Asset</th>
                                    <th class="border-0">Associated Post</th>
                                    <th class="border-0">Technical Info</th>
                                    <th class="border-0">Stats</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-right px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stems as $stem)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                @if($stem->featured_image)
                                                    <img src="{{ asset('storage/'.$stem->featured_image) }}" class="rounded shadow-sm" width="45" height="45" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-dark rounded d-flex align-items-center justify-center text-white" style="width: 45px; height: 45px;">
                                                        <i class="fas fa-compact-disc"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $stem->title }}</div>
                                                <div class="text-muted x-small uppercase tracking-wider">{{ $stem->file_size }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;">
                                            {{ $stem->thread->title ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-primary mr-1">{{ $stem->bpm }} BPM</span>
                                        <span class="badge badge-soft-danger">{{ $stem->music_key }}</span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="fas fa-download text-muted mr-1"></i> {{ $stem->interactions_count }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($stem->is_public)
                                            <span class="dot bg-success mr-1"></span> <small class="font-weight-bold uppercase">Public</small>
                                        @else
                                            <span class="dot bg-secondary mr-1"></span> <small class="font-weight-bold uppercase">Hidden</small>
                                        @endif
                                    </td>
                                    <td class="px-4 text-right">
                                        <div class="btn-group shadow-sm border rounded">
                                            <a href="{{ route('admin.stems.edit', $stem->id) }}" class="btn btn-white btn-sm px-3 border-right" title="Edit">
                                                <i class="fas fa-edit text-muted"></i>
                                            </a>
                                            <form action="{{ route('admin.stems.destroy', $stem->id) }}" method="POST" onsubmit="return confirm('Delete this asset?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-white btn-sm px-3 text-danger" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Pagination --}}
                <div class="card-footer bg-white border-0 py-3">
                    {{ $stems->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
