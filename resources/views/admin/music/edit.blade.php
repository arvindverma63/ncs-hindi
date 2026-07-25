<x-app-layout title="Edit Music Release | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    @php
        $selectedLanguages = collect(explode(',', (string) $music->language))
            ->map(fn ($language) => trim($language))
            ->filter()
            ->values()
            ->all();
    @endphp

    <div class="py-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.music.index') }}"
                                    class="text-decoration-none text-primary">Music Inventory</a></li>
                            <li class="breadcrumb-item active text-dark">Edit Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Edit: {{ $music->title }}</h3>
                    <p class="text-muted mb-0">Modify metadata or replace music assets in the vault.</p>
                </div>
            </div>

            <form id="musicUpdateForm" action="{{ route('admin.music.update', $music->id) }}" method="POST"
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
                                            value="{{ $music->title }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            value="{{ $music->artist_name }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Description</label>
                                        <textarea name="description" rows="4" class="form-control bg-light border-0"
                                            placeholder="Write a short release description, credits, or usage notes...">{{ $music->description }}</textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Languages
                                            <span class="text-muted">(multi-select)</span></label>
                                        <div id="language_options" class="d-flex flex-wrap gap-2">
                                            @foreach ($languages as $language)
                                                <input type="checkbox" class="btn-check"
                                                    id="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    value="{{ $language }}" autocomplete="off"
                                                    {{ in_array($language, $selectedLanguages, true) ? 'checked' : '' }}>
                                                <label for="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small text-nowrap">
                                                    {{ $language }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="language" id="language" value="{{ $music->language }}">
                                        <small class="text-muted mt-2 d-block">Pick one or more languages; we’ll store them in the release metadata.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select bg-light border-0" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $music->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            value="{{ $music->music_key }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1" {{ $music->is_public ? 'selected' : '' }}>Public
                                            </option>
                                            <option value="0" {{ !$music->is_public ? 'selected' : '' }}>Private
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <iconify-icon icon="mdi:folder-music-outline" class="fs-4"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark fs-6">Music Source & Audio Setup</h5>
                                        <span class="text-muted extra-small">Configure audio file storage & website streaming availability</span>
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace text-uppercase" style="font-size: 10px;">Config</span>
                            </div>
                            
                            <div class="card-body p-4">
                                {{-- Segmented Source Mode Selector --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-secondary mb-2 d-block">
                                        Select Music Source Mode <span class="text-danger">*</span>
                                    </label>
                                    
                                    @php
                                        $isUploadMode = $music->audio_file || !$music->mega_link;
                                    @endphp
                                    
                                    <div class="p-1.5 rounded-4 d-flex gap-2" style="background-color: #f1f5f9; border: 1px solid #e2e8f0;">
                                        <button type="button" id="btn_source_upload" class="btn {{ $isUploadMode ? 'btn-primary shadow-sm' : 'btn-light text-secondary border-0' }} w-50 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 transition-all">
                                            <iconify-icon icon="mdi:cloud-upload" class="fs-5"></iconify-icon>
                                            <span>Direct Audio Upload</span>
                                            <span class="badge {{ $isUploadMode ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }} ms-1 px-2 py-1 rounded-pill" style="font-size: 10px;">Web Stream</span>
                                        </button>
                                        
                                        <button type="button" id="btn_source_mega" class="btn {{ !$isUploadMode ? 'btn-primary shadow-sm' : 'btn-light text-secondary border-0' }} w-50 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 transition-all">
                                            <iconify-icon icon="mdi:cloud-link" class="fs-5"></iconify-icon>
                                            <span>Mega.nz Link Only</span>
                                            <span class="badge {{ !$isUploadMode ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }} ms-1 px-2 py-1 rounded-pill" style="font-size: 10px;">External Link</span>
                                        </button>
                                    </div>
                                    
                                    <input type="hidden" name="source_type" id="source_type_input" value="{{ $isUploadMode ? 'file' : 'mega' }}">
                                </div>

                                {{-- Direct File Upload Field --}}
                                <div id="file_upload_field_group" class="mb-4">
                                    <div class="alert alert-info border-0 bg-primary-subtle text-primary-emphasis rounded-3 py-2.5 px-3 small d-flex align-items-center gap-2 mb-3">
                                        <iconify-icon icon="mdi:information-outline" class="fs-5"></iconify-icon>
                                        <div>Uploading an audio file enables website visitors to stream and listen to this track in the web player.</div>
                                    </div>
                                    <div class="p-4 rounded-4 border-2 border-dashed bg-light text-center position-relative transition-all" style="border-color: #cbd5e1;">
                                        <div class="mb-2">
                                            <iconify-icon icon="mdi:file-music-outline" class="text-primary display-6"></iconify-icon>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Choose Audio File to Upload</h6>
                                        <p class="text-muted extra-small mb-3">Supported formats: <strong class="text-dark">MP3, WAV, OGG, M4A, AAC, FLAC</strong> (Max: 50MB)</p>
                                        <div class="d-inline-block">
                                            <input type="file" name="audio_file" id="audio_file" class="form-control form-control-sm bg-white border shadow-sm px-3 py-2 rounded-3" accept="audio/*">
                                        </div>
                                        @if ($music->audio_url)
                                            <div class="mt-3 p-3 bg-white rounded-3 border text-start shadow-sm" id="existingAudioContainer">
                                                <div class="small fw-bold text-success mb-2 d-flex align-items-center gap-1.5">
                                                    <iconify-icon icon="mdi:volume-high" class="fs-5"></iconify-icon> Current Active Audio File:
                                                </div>
                                                <audio controls src="{{ $music->audio_url }}" class="w-100" style="height: 38px;"></audio>
                                            </div>
                                        @endif
                                        <div id="selected_audio_filename" class="mt-2 font-monospace text-primary fw-bold small d-none"></div>
                                    </div>
                                </div>

                                {{-- Mega Link Field --}}
                                <div id="mega_link_field_group" class="mb-4 d-none">
                                    <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis rounded-3 py-2.5 px-3 small d-flex align-items-center gap-2 mb-3">
                                        <iconify-icon icon="mdi:alert-circle-outline" class="fs-5"></iconify-icon>
                                        <div>Mega link mode is for external cloud downloads. Website streaming player will be disabled for this track.</div>
                                    </div>
                                    <label class="form-label fw-bold small text-uppercase text-secondary mb-1">
                                        Mega.nz Storage Link <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3 text-secondary">
                                            <iconify-icon icon="mdi:link-variant" class="fs-5"></iconify-icon>
                                        </span>
                                        <input type="url" name="mega_link" id="mega_link" class="form-control bg-light border-start-0 py-2.5 rounded-end-3" value="{{ $music->mega_link }}" placeholder="https://mega.nz/file/...">
                                    </div>
                                    <small class="text-muted extra-small mt-1 d-block">Paste your public Mega.nz file or folder download link here.</small>
                                </div>

                                {{-- Website Playable Toggle Card --}}
                                <div class="rounded-4 bg-light border d-flex align-items-center justify-content-between mb-4" style="padding: 1rem 1.25rem !important;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 bg-white border text-warning d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0;">
                                            <iconify-icon icon="mdi:play-circle" class="fs-4 text-warning"></iconify-icon>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0">Enable Web Audio Player</div>
                                            <div class="text-muted extra-small">Allow website visitors to listen & stream this track live on NCS Hindi.</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch m-0 flex-shrink-0">
                                        <input class="form-check-input cursor-pointer ms-0" type="checkbox" name="can_play_on_website" id="can_play_on_website" value="1" {{ $music->can_play_on_website ? 'checked' : '' }} style="width: 2.75em; height: 1.4em;">
                                    </div>
                                </div>

                                {{-- YouTube Link --}}
                                <div>
                                    <label class="form-label fw-bold small text-uppercase text-secondary mb-1">
                                        YouTube Video Link <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3 text-danger">
                                            <iconify-icon icon="mdi:youtube" class="fs-5"></iconify-icon>
                                        </span>
                                        <input type="url" name="youtube_link" id="youtube_link" class="form-control bg-light border-start-0 py-2.5 rounded-end-3" value="{{ $music->youtube_link }}" placeholder="https://youtube.com/watch?v=...">
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
                                <img id="imagePreview" src="{{ $music->featured_image }}" alt="Cover Art Preview"
                                    class="w-100 h-100"
                                    style="{{ $music->featured_image ? '' : 'display: none;' }} object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4"
                                    style="{{ $music->featured_image ? 'display: none;' : '' }}">
                                    <iconify-icon icon="mdi:image-album" width="48"
                                        class="text-secondary opacity-50"></iconify-icon>
                                </div>
                            </div>
                            <div class="p-3 text-start">
                                <label class="small text-muted mb-2 d-block">Upload new to replace existing
                                    artwork</label>
                                <input type="file" name="featured_image" id="featured_image"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <div id="uploadProgressContainer" class="mb-4" style="display: none;">
                            <div class="progress" style="height: 8px;">
                                <div id="uploadProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow fw-bold text-uppercase">
                            <iconify-icon icon="mdi:check-circle" class="me-2"></iconify-icon> Update Release
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function() {
                // Image Preview logic
                $('#featured_image').on('change', function() {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        $('#imagePreview').attr('src', e.target.result).show();
                        $('#previewPlaceholder').hide();
                    };
                    if (this.files[0]) reader.readAsDataURL(this.files[0]);
                });

                // Unified AJAX Submission (Matches Create Logic)
                const syncLanguageField = () => {
                    const selectedLanguages = $('#language_options input[type="checkbox"]:checked').map(function() {
                        return $(this).val();
                    }).get();
                    $('#language').val(selectedLanguages.join(', '));
                };

                // Segmented Music Source Mode Toggle
                function setMusicSourceMode(mode) {
                    if (mode === 'file') {
                        $('#source_type_input').val('file');
                        $('#btn_source_upload')
                            .removeClass('btn-light text-secondary border-0')
                            .addClass('btn-primary shadow-sm')
                            .find('.badge').removeClass('bg-secondary-subtle text-secondary').addClass('bg-white text-primary');

                        $('#btn_source_mega')
                            .removeClass('btn-primary shadow-sm')
                            .addClass('btn-light text-secondary border-0')
                            .find('.badge').removeClass('bg-white text-primary').addClass('bg-secondary-subtle text-secondary');

                        $('#file_upload_field_group').removeClass('d-none').show();
                        $('#mega_link_field_group').addClass('d-none').hide();
                    } else {
                        $('#source_type_input').val('mega');
                        $('#btn_source_mega')
                            .removeClass('btn-light text-secondary border-0')
                            .addClass('btn-primary shadow-sm')
                            .find('.badge').removeClass('bg-secondary-subtle text-secondary').addClass('bg-white text-primary');

                        $('#btn_source_upload')
                            .removeClass('btn-primary shadow-sm')
                            .addClass('btn-light text-secondary border-0')
                            .find('.badge').removeClass('bg-white text-primary').addClass('bg-secondary-subtle text-secondary');

                        $('#file_upload_field_group').addClass('d-none').hide();
                        $('#mega_link_field_group').removeClass('d-none').show();
                        $('#can_play_on_website').prop('checked', false);
                    }
                }

                $('#btn_source_upload').on('click', function() { setMusicSourceMode('file'); });
                $('#btn_source_mega').on('click', function() { setMusicSourceMode('mega'); });

                $('#audio_file').on('change', function() {
                    if (this.files && this.files[0]) {
                        $('#selected_audio_filename').text('New Selected File: ' + this.files[0].name).removeClass('d-none');
                    } else {
                        $('#selected_audio_filename').addClass('d-none');
                    }
                });

                $('#musicUpdateForm').on('submit', function(e) {
                    e.preventDefault();
                    syncLanguageField();

                    const mode = $('#source_type_input').val();
                    const megaLink = $('#mega_link').val();

                    if (mode === 'mega' && !megaLink) {
                        toastr.error('Mega Link is required when selecting Mega.nz link mode.');
                        return false;
                    }

                    let formData = new FormData(this);

                    if (megaLink) {
                        formData.append('music_file', megaLink);
                    } else {
                        formData.append('music_file', 'Direct Audio Upload');
                    }

                    let $btn = $('#btnSubmit');
                    let $progress = $('#uploadProgressContainer');

                    $btn.prop('disabled', true).html(
                        '<iconify-icon icon="line-md:loading-twotone-loop" class="me-2"></iconify-icon> Updating...'
                        );

                    // Only show progress if a new featured image is being uploaded
                    if ($('#featured_image').val()) {
                        $progress.slideDown();
                    }

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST', // Form handles Method Spoofing (@method('PUT'))
                        data: formData,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            let xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    let p = Math.round((evt.loaded / evt.total) * 100);
                                    $('#uploadProgressBar').css('width', p + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function(res) {
                            toastr.success('Music asset updated successfully!');
                            setTimeout(() => window.location.href =
                                "{{ route('admin.music.index') }}", 1500);
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false).html(
                                '<iconify-icon icon="mdi:check-circle" class="me-2"></iconify-icon> Update Release'
                                );
                            $progress.hide();

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error(
                                    'Update failed. Check your connection or server limits.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>







