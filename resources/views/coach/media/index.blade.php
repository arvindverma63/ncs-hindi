<x-coach-layout title="Media Gallery | CoffeePass">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h4 class="fs-18 fw-bold m-0 text-dark">Media Gallery</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 font-size-12">
                                <li class="breadcrumb-item"><a href="#">Coach</a></li>
                                <li class="breadcrumb-item active">My Media</li>
                            </ol>
                        </nav>
                    </div>

                    <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal"
                        data-bs-target="#uploadMediaModal">
                        <i class="mdi mdi-upload-variant me-1"></i> Upload New Asset
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('coach.media.index') }}" class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="mdi mdi-magnify text-muted"></i></span>
                                    <input type="text" name="search" class="form-control bg-light border-0"
                                        placeholder="Search my files..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <select name="category_id" class="form-select bg-light border-0">
                                    <option value="">All Categories</option>
                                    @foreach (App\Models\Category::where('is_active', 1)->get() as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <select name="type" class="form-select bg-light border-0">
                                    <option value="">All Types</option>
                                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images
                                    </option>
                                    <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Books/PDFs
                                    </option>
                                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos
                                    </option>
                                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>
                                        Documents</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-dark flex-grow-1">Apply Filters</button>
                                <a href="{{ route('coach.media.index') }}" class="btn btn-light" title="Reset"><i
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
                                <img src="{{ asset($item->url) }}" class="w-100 h-100 object-fit-cover"
                                    alt="{{ $item->title }}">
                            @else
                                <div class="text-center">
                                    <i class="mdi {{ $item->icon }} text-secondary opacity-50"
                                        style="font-size: 64px;"></i>
                                    <span
                                        class="d-block text-uppercase fw-bold font-size-11 text-muted mt-n2">{{ $item->extension }}</span>
                                </div>
                            @endif

                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge bg-primary shadow-sm font-size-10">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            <div class="position-absolute top-0 end-0 p-2">
                                <span
                                    class="badge bg-dark bg-opacity-75 font-size-10">{{ $item->readable_size }}</span>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <h6 class="text-dark fw-bold mb-2 text-truncate" title="{{ $item->title }}">
                                {{ $item->title }}</h6>

                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center text-muted font-size-12">
                                    <i class="mdi mdi-calendar-outline me-1"></i>
                                    <span>{{ $item->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 pt-0 d-flex gap-2 pb-3 px-3">
                            <a href="{{ asset($item->url) }}" target="_blank"
                                class="btn btn-sm btn-primary flex-grow-1">
                                <i class="mdi mdi-eye-outline me-1"></i>View
                            </a>

                            <form action="{{ route('coach.media.destroy', $item->id) }}" method="POST"
                                class="delete-form">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger confirm-delete px-3">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="avatar-lg bg-soft-secondary rounded-circle mx-auto mb-3"
                        style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-folder-open-outline text-muted fs-1" style="font-size: 40px;"></i>
                    </div>
                    <h5 class="text-muted">You haven't uploaded any media yet.</h5>
                </div>
            @endforelse
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center mt-3">
                {{ $items->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form id="mediaUploadForm" action="{{ route('coach.media.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">Upload New Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Display Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                placeholder="e.g. Session Handout PDF" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="" selected disabled>Select a Category</option>
                                @foreach (App\Models\Category::where('is_active', 1)->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Select File <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="file" id="mediaFile" class="form-control" required>
                            <div class="form-text mt-2 font-size-12">
                                Max size: 50MB. Supported: Images, PDFs, and Documents.
                            </div>
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
                                id="btnText">Start Upload</span></button>
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
                        title: 'Delete this file?',
                        text: "This action will permanently remove the asset from your gallery.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        customClass: {
                            confirmButton: 'btn btn-danger px-4 me-2',
                            cancelButton: 'btn btn-light px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) $form.submit();
                    });
                });

                // 2. Upload UI Handler
                $('#mediaFile').on('change', function() {
                    if (this.files[0].size > 50 * 1024 * 1024) {
                        Swal.fire('File Too Large', 'Please select a file smaller than 50MB.', 'error');
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
</x-coach-layout>
