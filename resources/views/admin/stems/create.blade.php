<x-app-layout title="Upload Official Stem | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
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
                                            class="form-control form-control-lg bg-light border-0 @error('title') is-invalid @enderror"
                                            placeholder="e.g., Baarishein - Lo-Fi" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            placeholder="e.g., Anuv Jain" value="{{ old('artist_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Album/Movie
                                            Name</label>
                                        <input type="text" name="album_movie_name"
                                            class="form-control bg-light border-0" placeholder="e.g., Indie Hits 2026"
                                            value="{{ old('album_movie_name') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id"
                                            class="form-select bg-light border-0 @error('category_id') is-invalid @enderror"
                                            required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Language</label>
                                        <input type="text" name="language" class="form-control bg-light border-0"
                                            placeholder="e.g., Hindi, Punjabi" value="{{ old('language') }}">
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
                                        <input type="number" name="bpm" class="form-control bg-light border-0"
                                            placeholder="128" value="{{ old('bpm') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            placeholder="Am, C#m" value="{{ old('music_key') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1">Public</option>
                                            <option value="0">Private / Draft</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Description</label>
                                        <textarea name="description" class="form-control bg-light border-0" rows="4"
                                            placeholder="Briefly describe the track...">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div
                                class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:google"
                                        class="me-2 text-primary"></iconify-icon>SEO & Tags</h5>
                                <span class="badge bg-light text-primary fw-normal border">Optional</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Tags /
                                        Keywords (Comma Separated)</label>
                                    <input type="text" name="tags_keywords" class="form-control bg-light border-0"
                                        placeholder="lofi, hiphop, romantic, stems, ncs"
                                        value="{{ old('tags_keywords') }}">
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">SEO
                                            Title</label>
                                        <input type="text" name="seo_title" class="form-control bg-light border-0"
                                            placeholder="Custom Meta Title">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">SEO
                                            Description</label>
                                        <input type="text" name="seo_description"
                                            class="form-control bg-light border-0"
                                            placeholder="Short Meta Description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="card-title mb-0 fw-bold text-dark">Music Cover Image</h6>
                            </div>
                            <div id="imagePreviewContainer"
                                class="bg-light d-flex align-items-center justify-content-center border-bottom position-relative"
                                style="height: 300px;">
                                <img id="imagePreview" src="" class="w-100 h-100"
                                    style="display: none; object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4">
                                    <iconify-icon icon="mdi:image-album" width="64"
                                        class="mb-2 text-secondary opacity-50"></iconify-icon>
                                    <p class="small mb-0 text-muted">Square (1:1) Recommended</p>
                                </div>
                            </div>
                            <div class="p-3">
                                <input type="file" name="featured_image" id="coverImg"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3"
                                    style="width: 64px; height: 64px;">
                                    <iconify-icon icon="mdi:file-music" class="text-primary"
                                        width="32"></iconify-icon>
                                </div>
                                <h6 class="fw-bold text-uppercase small mb-2">MP3 Audio File</h6>
                                <p class="text-muted small mb-3">Upload your .mp3 audio (Max: 50MB)</p>
                                <input type="file" name="stem_file" id="stemFile"
                                    class="form-control border-0 bg-light @error('stem_file') is-invalid @enderror"
                                    accept=".mp3" required>
                                @error('stem_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-primary btn-lg w-100 py-3 rounded-3 shadow-lg fw-bold text-uppercase">
                            <iconify-icon icon="mdi:cloud-upload" class="me-2"
                                style="vertical-align: middle;"></iconify-icon>
                            Publish Music
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Image Previewer
            document.getElementById('coverImg').addEventListener('change', function(e) {
                const reader = new FileReader();
                const preview = document.getElementById('imagePreview');
                const placeholder = document.getElementById('previewPlaceholder');

                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
            });

            // Submission Loading State
            document.getElementById('stemUploadForm').addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmit');
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
            });
        </script>
        <style>
            .form-control:focus,
            .form-select:focus {
                background-color: #fff !important;
                border: 1px solid #0d6efd !important;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
            }

            .card {
                transition: transform 0.2s ease;
            }

            .card:hover {
                transform: translateY(-2px);
            }
        </style>
    @endpush
</x-app-layout>
