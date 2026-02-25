<x-app-layout title="Seeker Management | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Manage Seekers</h4>
                    <p class="text-muted mb-0">Monitor registered users and their activity.</p>
                </div>
                <div class="text-end mb-3">
                    <a href="{{ route('admin.seekers.export.excel') }}" class="btn btn-success me-2">
                        <iconify-icon icon="tabler:file-spreadsheet" class="align-middle fs-16 me-1"></iconify-icon>
                        Excel
                    </a>

                    <a href="{{ route('admin.seekers.export.pdf') }}" class="btn btn-danger me-2">
                        <iconify-icon icon="tabler:file-type-pdf" class="align-middle fs-16 me-1"></iconify-icon>
                        PDF
                    </a>
                    <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                        data-bs-target="#importSeekerModal">
                        <iconify-icon icon="tabler:upload" class="align-middle fs-16 me-1"></iconify-icon>
                        Import
                    </button>
                    <a href="{{ route('admin.seekers.create') }}" class="btn btn-primary">
                        <iconify-icon icon="tabler:plus" class="align-middle fs-16 me-1"></iconify-icon>
                        Add Seeker
                    </a>
                </div>
            </div>
            <div class="modal fade" id="importSeekerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Seekers (Excel)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="importSeekerForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Choose Excel File</label>
                                    <input type="file" name="file" class="form-control" required
                                        accept=".xlsx,.xls,.csv">
                                    <small class="text-muted">Columns: name, email, company_name, business_domain, city,
                                        state</small>
                                </div>

                                <div class="progress d-none mb-3" id="progressBarContainer" style="height: 20px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" style="width: 0%;" id="progressBar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>

                                <div id="importStatus" class="mt-2"></div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="btnUpload">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.seekers.index') }}" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search Seeker</label>
                                <input type="text" name="search" class="form-control" placeholder="Name or Email..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Verification Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>
                                        Verified</option>
                                    <option value="unverified"
                                        {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-5 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <iconify-icon icon="tabler:filter" class="align-middle"></iconify-icon> Filter
                                </button>
                                <a href="{{ route('admin.seekers.index') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="text-danger small mt-2">
                                {{ $errors->first() }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Seeker Name</th>
                                            <th>Business Interest</th>
                                            <th>Location</th>
                                            <th>Verification</th>
                                            <th>Joined</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($seekers as $seeker)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0 me-2">
                                                            <span
                                                                class="avatar-title bg-primary-subtle text-primary rounded-circle fs-16">
                                                                {{-- Safe check: Get first letter or '?' --}}
                                                                {{ strtoupper(substr($seeker->user->name ?? '?', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            {{-- Safe check: Show name or 'User Not Found' --}}
                                                            <h5 class="m-0 font-14">
                                                                {{ $seeker->user->name ?? 'User Not Found' }}</h5>
                                                            <small
                                                                class="text-muted">{{ $seeker->user->email ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($seeker->business_domain)
                                                        <span
                                                            class="badge bg-light text-dark border">{{ $seeker->business_domain }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                    @if ($seeker->company_name)
                                                        <div class="small text-muted mt-1">{{ $seeker->company_name }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="mdi mdi-map-marker-outline text-muted"></i>
                                                    {{ $seeker->city ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($seeker->is_verified)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="mdi mdi-check-decagram"></i> Verified
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-warning-subtle text-warning">Unverified</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $seeker->created_at->format('d M, Y') }}
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.seekers.show', $seeker->id) }}"
                                                        class="btn btn-sm btn-soft-info">
                                                        <i class="mdi mdi-eye-outline"></i> View
                                                    </a>

                                                    <form action="{{ route('admin.seekers.destroy', $seeker->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger">
                                                            <i class="mdi mdi-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">No seekers
                                                    found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $seekers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#importSeekerForm').on('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);
                    let progressBar = $('#progressBar');
                    let progressContainer = $('#progressBarContainer');
                    let statusDiv = $('#importStatus');
                    let btnUpload = $('#btnUpload');

                    // Reset UI
                    progressContainer.removeClass('d-none');
                    progressBar.width('0%').text('0%').removeClass('bg-danger bg-success');
                    statusDiv.html('');
                    btnUpload.prop('disabled', true);

                    $.ajax({
                        xhr: function() {
                            let xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    let percentComplete = Math.round((evt.loaded / evt
                                        .total) * 100);
                                    progressBar.width(percentComplete + '%').text(
                                        percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        url: "{{ route('admin.seekers.import') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            progressBar.width('100%').text('Completed').addClass('bg-success');
                            statusDiv.html('<div class="alert alert-success py-2">' + response
                                .success + '</div>');

                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            progressBar.addClass('bg-danger');
                            let errorMsg = 'Upload failed.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            statusDiv.html('<div class="alert alert-danger py-2">' + errorMsg +
                                '</div>');
                            btnUpload.prop('disabled', false);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
