<x-app-layout title="Coach Management | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Manage Coaches</h4>
                </div>
                <div class="text-end">
                    <div>
                        <a href="{{ route('admin.coaches.export.excel') }}" class="btn btn-success me-2">
                            <iconify-icon icon="tabler:file-spreadsheet" class="align-middle fs-16 me-1"></iconify-icon>
                            Excel
                        </a>

                        <a href="{{ route('admin.coaches.export.pdf') }}" class="btn btn-danger me-2">
                            <iconify-icon icon="tabler:file-type-pdf" class="align-middle fs-16 me-1"></iconify-icon>
                            PDF
                        </a>

                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            <iconify-icon icon="tabler:upload" class="align-middle fs-16 me-1"></iconify-icon>
                            Import
                        </button>

                        <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary">
                            <iconify-icon icon="tabler:plus" class="align-middle fs-16 me-1"></iconify-icon>
                            Add Coach
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Coaches (Excel)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="importForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Choose Excel File</label>
                                    <input type="file" name="file" class="form-control" id="importFile" required
                                        accept=".xlsx,.xls,.csv">
                                    <small class="text-muted">Columns required: name, email, designation, company_name,
                                        city, experience_years</small>
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
                    <form action="{{ route('admin.coaches.index') }}" method="GET" id="coachFilterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search Coach</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Name, Email or Company..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Approval Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-5 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <iconify-icon icon="tabler:filter" class="align-middle"></iconify-icon> Filter
                                </button>
                                <a href="{{ route('admin.coaches.index') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="text-danger small mt-2">{{ $errors->first() }}</div>
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
                                            <th>Coach Name</th>
                                            <th>Expertise</th>
                                            <th>Status</th>
                                            <th>Ranking</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($coaches as $coach)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            // Handle Image from User model (via Spatie)
                                                            $img =
                                                                $coach->user->profile ?:
                                                                'https://ui-avatars.com/api/?name=' .
                                                                    $coach->user->name;
                                                        @endphp
                                                        <img src="{{ $img }}"
                                                            class="rounded-circle avatar-sm me-3">
                                                        <div>
                                                            <h5 class="m-0 font-14">{{ $coach->user->name }}</h5>
                                                            <small
                                                                class="text-muted">{{ $coach->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="d-block text-dark fw-medium">{{ $coach->designation ?? 'N/A' }}</span>
                                                    <small class="text-muted">{{ $coach->company_name }}</small>
                                                </td>
                                                <td>
                                                    @if ($coach->approval_status === 'approved')
                                                        <span
                                                            class="badge bg-success-subtle text-success">Approved</span>
                                                    @elseif($coach->approval_status === 'rejected')
                                                        <span
                                                            class="badge bg-danger-subtle text-danger">Rejected</span>
                                                    @else
                                                        <span
                                                            class="badge bg-warning-subtle text-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="mdi mdi-trophy-variant-outline text-warning"></i>
                                                    {{ $coach->current_rank ?? '-' }}
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.coaches.edit', $coach->id) }}"
                                                        class="btn btn-sm btn-soft-info">
                                                        <i class="mdi mdi-pencil"></i> Manage
                                                    </a>

                                                    <form action="{{ route('admin.coaches.destroy', $coach->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger"><i
                                                                class="mdi mdi-trash-can"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">No coaches
                                                    found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="">{{ $coaches->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#importForm').on('submit', function(e) {
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
                        url: "{{ route('admin.coaches.import') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            progressBar.width('100%').text('Completed').addClass('bg-success');
                            statusDiv.html('<div class="alert alert-success py-2">' + response
                                .success + '</div>');

                            // Reload page after 2 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            progressBar.addClass('bg-danger');
                            let errorMsg = 'Upload failed.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON
                                    .message; // Validation errors often come as 'message'
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
