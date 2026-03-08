<x-app-layout title="Upload Official Stem | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    <div class="py-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.stems.index') }}"
                                    class="text-decoration-none text-primary">Inventory</a></li>
                            <li class="breadcrumb-item active text-dark">New Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Initialize Official Release</h3>
                    <p class="text-muted mb-0">Fill in the metadata and deploy .mp3 assets to the vault.</p>
                </div>
            </div>

            <form id="stemUploadForm" action="{{ route('admin.stems.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:music-box-outline"
                                        class="me-2 text-primary"></iconify-icon>Music Metadata</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control form-control-lg bg-light border-0"
                                            placeholder="e.g., Baarishein - Lo-Fi" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            placeholder="e.g., Anuv Jain">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Album/Movie
                                            Name</label>
                                        <input type="text" name="album_movie_name"
                                            class="form-control bg-light border-0" placeholder="e.g., Indie Hits 2026">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select bg-light border-0" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Language</label>
                                        <input type="text" name="language" class="form-control bg-light border-0"
                                            placeholder="e.g., Hindi">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:tune-vertical"
                                        class="me-2 text-primary"></iconify-icon>Musical Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">BPM</label>
                                        <input type="number" name="bpm" class="form-control bg-light border-0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1">Public</option>
                                            <option value="0">Private</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Description</label>
                                        <textarea name="description" class="form-control bg-light border-0" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:google"
                                        class="me-2 text-primary"></iconify-icon>SEO & Tags</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Tags</label>
                                    <input type="text" name="tags_keywords" class="form-control bg-light border-0">
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">SEO
                                            Title</label>
                                        <input type="text" name="seo_title"
                                            class="form-control bg-light border-0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">SEO
                                            Description</label>
                                        <input type="text" name="seo_description"
                                            class="form-control bg-light border-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                            <div class="card-header bg-white border-bottom py-3 text-start">
                                <h6 class="card-title mb-0 fw-bold text-dark">Cover Art</h6>
                            </div>
                            <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                style="height: 250px;">
                                <img id="imagePreview" src="" class="w-100 h-100"
                                    style="display: none; object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4">
                                    <iconify-icon icon="mdi:image-album" width="48"
                                        class="text-secondary opacity-50"></iconify-icon>
                                </div>
                            </div>
                            <div class="p-3">
                                <input type="file" name="featured_image" id="featured_image"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-uppercase small mb-3">Audio (.mp3) <span
                                        class="text-danger">*</span></h6>
                                <input type="file" name="stem_file" id="stem_file"
                                    class="form-control border-0 bg-light" accept=".mp3" required>
                            </div>
                        </div>

                        <div id="uploadProgressContainer" class="mb-4" style="display: none;">
                            <div class="d-flex justify-content-between mb-1 small fw-bold text-primary">
                                <span id="statusText">Uploading...</span>
                                <span id="uploadPercentage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="uploadProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-primary btn-lg w-100 py-3 rounded-3 shadow fw-bold text-uppercase">
                            Publish Music
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function() {
                // 1. Image Preview Logic
                $('#featured_image').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imagePreview').attr('src', e.target.result).show();
                            $('#previewPlaceholder').hide();
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // 2. Custom Validation Method for File Size (Optional but recommended)
                $.validator.addMethod("filesize", function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param);
                }, "File size must be less than {0} bytes");

                // 3. Form Validation and AJAX Submission
                $("#stemUploadForm").validate({
                    rules: {
                        title: {
                            required: true,
                            maxlength: 255
                        },
                        category_id: {
                            required: true
                        },
                        stem_file: {
                            required: true,
                            extension: "mp3" // Fixes the 'valid mimetype' error by checking extension instead
                        },
                        featured_image: {
                            extension: "jpg|jpeg|png|webp"
                        },
                        bpm: {
                            digits: true
                        }
                    },
                    messages: {
                        title: "Please enter a music title.",
                        category_id: "Please select a category.",
                        stem_file: {
                            required: "Audio file is required.",
                            extension: "Only .mp3 files are allowed."
                        },
                        featured_image: {
                            extension: "Please upload a valid image (jpg, jpeg, png, or webp)."
                        }
                    },
                    errorElement: 'span',
                    errorClass: 'text-danger small mt-1 d-block',
                    highlight: function(element) {
                        $(element).addClass('is-invalid border-danger');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid border-danger');
                    },

                    submitHandler: function(form, event) {
                        event.preventDefault(); // Prevents the default page refresh

                        let formData = new FormData(form);
                        let $btn = $('#btnSubmit');
                        let $progressContainer = $('#uploadProgressContainer');
                        let $progressBar = $('#uploadProgressBar');
                        let $percentage = $('#uploadPercentage');
                        let $status = $('#statusText');

                        // UI State Change
                        $btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm me-2"></span> Publishing...');
                        $progressContainer.show();

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                let xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        let percentComplete = Math.round((evt.loaded /
                                            evt.total) * 100);
                                        $progressBar.css('width', percentComplete +
                                        '%');
                                        $percentage.text(percentComplete + '%');

                                        if (percentComplete === 100) {
                                            $status.text(
                                                'Finalizing assets on server...');
                                        }
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(response) {
                                toastr.success('Music asset published successfully!');
                                setTimeout(function() {
                                    window.location.href =
                                        "{{ route('admin.stems.index') }}";
                                }, 1500);
                            },
                            error: function(xhr) {
                                $btn.prop('disabled', false).text('Publish Music');
                                $progressContainer.hide();

                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    $.each(errors, function(key, value) {
                                        toastr.error(value[0]);
                                    });
                                } else {
                                    toastr.error(
                                        'Upload failed. Check your internet or server file size limits.'
                                        );
                                }
                            }
                        });

                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
