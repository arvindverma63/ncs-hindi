<x-app-layout title="Edit Release | NCS Hindi Admin">
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
                            <li class="breadcrumb-item active text-dark">Edit Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Edit: {{ $stem->title }}</h3>
                    <p class="text-muted mb-0">Modify metadata or replace studio assets in the vault.</p>
                </div>
            </div>

            <form id="stemUpdateForm" action="{{ route('admin.stems.update', $stem->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                            value="{{ $stem->title }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            value="{{ $stem->artist_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Album/Movie
                                            Name</label>
                                        <input type="text" name="album_movie_name"
                                            class="form-control bg-light border-0"
                                            value="{{ $stem->album_movie_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select bg-light border-0" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $stem->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Language</label>
                                        <input type="text" name="language" class="form-control bg-light border-0"
                                            value="{{ $stem->language }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3 text-dark fw-bold">Musical Details</div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">BPM</label>
                                        <input type="number" name="bpm" class="form-control bg-light border-0"
                                            value="{{ $stem->bpm }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            value="{{ $stem->music_key }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1" {{ $stem->is_public ? 'selected' : '' }}>Public
                                            </option>
                                            <option value="0" {{ !$stem->is_public ? 'selected' : '' }}>Private
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Description</label>
                                        <textarea name="description" class="form-control bg-light border-0" rows="3">{{ $stem->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3 text-start fw-bold">Cover Art</div>
                            <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                style="height: 250px;">
                                <img id="imagePreview" src="{{  $stem->featured_image ? asset('storage/' . $stem->featured_image) : '' }}" alt="Cover Art Preview"
                                    class="w-100 h-100"
                                    style="{{ $stem->featured_image ? '' : 'display: none;' }} object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4"
                                    style="{{ $stem->featured_image ? 'display: none;' : '' }}">
                                    <iconify-icon icon="mdi:image-album" width="48"
                                        class="text-secondary opacity-50"></iconify-icon>
                                </div>
                            </div>
                            <div class="p-3 text-start">
                                <label class="small text-muted mb-2 d-block">Upload new to replace existing</label>
                                <input type="file" name="featured_image" id="featured_image"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                            <div class="card-body p-4 text-start">
                                <h6 class="fw-bold text-uppercase small mb-3">Audio (.mp3)</h6>
                                <p class="small text-muted mb-3 text-truncate"><iconify-icon
                                        icon="mdi:music-note"></iconify-icon> Current: {{ $stem->file_name }}</p>
                                <input type="file" name="stem_file" id="stem_file"
                                    class="form-control border-0 bg-light" accept=".mp3">
                            </div>
                        </div>

                        <div id="uploadProgressContainer" class="mb-4" style="display: none;">
                            <div class="d-flex justify-content-between mb-1 small fw-bold text-primary">
                                <span id="statusText">Updating...</span>
                                <span id="uploadPercentage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="uploadProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow fw-bold text-uppercase">
                            Update Release
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#featured_image').on('change', function() {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        $('#imagePreview').attr('src', e.target.result).show();
                        $('#previewPlaceholder').hide();
                    };
                    if (this.files[0]) reader.readAsDataURL(this.files[0]);
                });

                $.validator.addMethod("mp3Only", function(value, element) {
                    return this.optional(element) || /.mp3$/i.test(value);
                }, "Please upload a valid .mp3 file");

                $.validator.addMethod("imageOnly", function(value, element) {
                    return this.optional(element) || /.(jpg|jpeg|png|webp)$/i.test(value);
                }, "Supported: JPG, PNG, WEBP");

                $("#stemUpdateForm").validate({
                    rules: {
                        title: "required",
                        category_id: "required",
                        stem_file: {
                            mp3Only: true
                        },
                        featured_image: {
                            imageOnly: true
                        }
                    },
                    errorElement: 'span',
                    errorClass: 'text-danger small d-block mt-1',
                    highlight: function(element) {
                        $(element).addClass('border border-danger');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('border border-danger');
                    },

                    submitHandler: function(form, event) {
                        event.preventDefault();
                        let formData = new FormData(form);
                        let $btn = $('#btnSubmit');
                        let $progress = $('#uploadProgressContainer');

                        $btn.prop('disabled', true).text('Updating...');
                        $progress.show();

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST', // Still POST because of _method PUT
                            data: formData,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                let xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        let p = Math.round((evt.loaded / evt.total) *
                                            100);
                                        $('#uploadProgressBar').css('width', p + '%');
                                        $('#uploadPercentage').text(p + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(res) {
                                toastr.success('Release updated successfully!');
                                setTimeout(() => window.location.href =
                                    "{{ route('admin.stems.index') }}", 1500);
                            },
                            error: function(xhr) {
                                $btn.prop('disabled', false).text('Update Release');
                                $progress.hide();
                                if (xhr.status === 422) {
                                    $.each(xhr.responseJSON.errors, (k, v) => toastr.error(v[
                                        0]));
                                } else {
                                    toastr.error('Update failed. Check server limits.');
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
