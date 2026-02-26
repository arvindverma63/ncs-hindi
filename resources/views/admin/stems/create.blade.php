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
                            <li class="breadcrumb-item active">New Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Initialize Official Release</h3>
                    <p class="text-muted mb-0">Select a category and deploy assets to the vault.</p>
                </div>
            </div>

            <form id="stemUploadForm" action="{{ route('admin.stems.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">Release Metadata</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Asset Title
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control form-control-lg bg-light border-0 @error('title') is-invalid @enderror"
                                        placeholder="e.g., Bollywood Trap - Lead Vocals" value="{{ old('title') }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Technical
                                        Notes & License</label>
                                    <textarea name="description" class="form-control bg-light border-0" rows="6"
                                        placeholder="Describe the stem contents and usage rights...">{{ old('description') }}</textarea>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Asset
                                            Category <span class="text-danger">*</span></label>
                                        <select name="category_id"
                                            class="form-select bg-light border-0 @error('category_id') is-invalid @enderror"
                                            required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">BPM</label>
                                        <input type="number" name="bpm" class="form-control bg-light border-0"
                                            placeholder="128">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Scale /
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            placeholder="F# Minor">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="card-title mb-0 fw-bold text-dark">Cover Art</h6>
                            </div>
                            <div class="card-body p-0">
                                <div id="imagePreviewContainer"
                                    class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                    style="height: 250px;">
                                    <img id="imagePreview" src="" class="w-100 h-100"
                                        style="display: none; object-fit: cover;">
                                    <div id="previewPlaceholder" class="text-center text-muted p-4">
                                        <iconify-icon icon="mdi:image-filter-hdr" width="48"
                                            class="mb-2 text-secondary"></iconify-icon>
                                        <p class="small mb-0 fw-bold">Cover Preview</p>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <input type="file" name="featured_image" id="coverImg"
                                        class="form-control form-control-sm border-0 bg-light" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3"
                                    style="width: 64px; height: 64px;">
                                    <iconify-icon icon="mdi:music-note-plus" class="text-primary"
                                        width="32"></iconify-icon>
                                </div>
                                <h6 class="fw-bold text-uppercase small mb-3">Audio Archive</h6>
                                <input type="file" name="stem_file" id="stemFile"
                                    class="form-control border-0 bg-light" required>
                                <div class="mt-3 p-2 bg-light rounded text-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="mdi:shield-check-outline"
                                            class="text-success"></iconify-icon>
                                        <span class="text-secondary fw-bold" style="font-size: 10px;">SECURE 500MB
                                            UPLOAD</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 py-3 rounded-3 shadow-lg fw-bold text-uppercase">
                            <iconify-icon icon="mdi:cloud-upload" class="me-2"
                                style="vertical-align: sub;"></iconify-icon>
                            Publish to Vault
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
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
        </script>
        <style>
            .form-control:focus,
            .form-select:focus {
                background-color: #fff !important;
                border: 1px solid #0d6efd !important;
                box-shadow: none;
            }
        </style>
    @endpush
</x-app-layout>
