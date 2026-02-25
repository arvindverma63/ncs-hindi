<x-app-layout title="Media Gallery | CoffeePass Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-dark py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fs-18 fw-bold m-0">Media Gallery</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 font-size-12">
                                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                                <li class="breadcrumb-item active">Media Assets</li>
                            </ol>
                        </nav>
                    </div>
                    @can('media.upload')
                        <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal"
                            data-bs-target="#uploadMediaModal">
                            <i class="mdi mdi-upload-variant me-1"></i> Upload New Asset
                        </button>
                    @endcan
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('admin.media.index') }}" class="row g-3"
                                id="filterForm">
                                <div class="col-lg-4 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="mdi mdi-magnify text-muted"></i></span>
                                        <input type="text" name="search" class="form-control bg-light border-0"
                                            placeholder="Search by title..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <select name="category_id" class="form-select bg-light border-0">
                                        <option value="">All Categories</option>
                                        @foreach (App\Models\Category::where('is_active', 1)->get() as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <select name="type" class="form-select bg-light border-0">
                                        <option value="">All Types</option>
                                        <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images
                                        </option>
                                        <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Books
                                        </option>
                                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos
                                        </option>
                                        <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>
                                            Documents</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-dark flex-grow-1">Apply Filters</button>
                                    <a href="{{ route('admin.media.index') }}" class="btn btn-light"><i
                                            class="mdi mdi-refresh"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($items as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden transition-all hover-shadow">
                            <div class="bg-light d-flex align-items-center justify-content-center position-relative"
                                style="height: 180px;">
                                @if ($item->file_type == 'image')
                                    <img src="{{ asset($item->url) }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="text-center">
                                        <i class="mdi {{ $item->icon }} text-secondary opacity-50"
                                            style="font-size: 64px;"></i>
                                        <span
                                            class="d-block text-uppercase fw-bold font-size-11 text-muted">{{ $item->extension }}</span>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 p-2">
                                    <span
                                        class="badge bg-primary shadow-sm">{{ $item->category->name ?? 'Uncategorized' }}</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="text-dark fw-bold mb-2 text-truncate">{{ $item->title }}</h6>
                                <small class="text-muted d-block">By: <span
                                        class="text-primary">{{ $item->user->name ?? 'Admin' }}</span></small>
                                <small class="text-muted">{{ $item->created_at->format('d M, Y') }}</small>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex gap-2 pb-3 px-3">
                                <a href="{{ asset($item->url) }}" target="_blank"
                                    class="btn btn-sm btn-primary flex-grow-1">View</a>
                                <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger confirm-delete"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No assets found matching your criteria.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">Upload New Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach (App\Models\Category::where('is_active', 1)->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="mediaFile" class="form-control" required>
                            <div class="form-text mt-2 font-size-12">Max size: 50MB.</div>
                        </div>
                        <div class="progress mt-3 d-none" id="uploadProgressContainer" style="height: 10px;">
                            <div id="uploadProgressBar"
                                class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary px-4"><span
                                id="btnText">Upload</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // 1. Delete Handler (Event Delegation)
                $(document).on('click', '.confirm-delete', function(e) {
                    e.preventDefault();
                    const $form = $(this).closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Permanently delete this asset?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete!',
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-light'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) $form.submit();
                    });
                });

                // 2. Upload Handler
                $('#mediaFile').on('change', function() {
                    if (this.files[0].size > 50 * 1024 * 1024) {
                        Swal.fire('Error', 'File exceeds 50MB limit.', 'error');
                        $(this).val('');
                    }
                });

                $('#mediaUploadForm').on('submit', function() {
                    $('#submitBtn').prop('disabled', true);
                    $('#btnText').text('Uploading...');
                    $('#uploadProgressContainer').removeClass('d-none');
                    let p = 0;
                    const timer = setInterval(() => {
                        p += (p < 90) ? 5 : 0.2;
                        $('#uploadProgressBar').css('width', p + '%');
                        if (p >= 99) clearInterval(timer);
                    }, 250);
                });
            });
        </script>
    @endpush
</x-app-layout>
