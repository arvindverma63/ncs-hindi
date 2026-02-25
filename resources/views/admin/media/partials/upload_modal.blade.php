<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="mdi mdi-cloud-upload-outline me-1"></i> Upload New Asset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="mediaTitle" class="form-control" 
                               placeholder="e.g. Q1 Growth Report" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Assign Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select select2-modal" required>
                            <option value="" selected disabled>Choose a business domain...</option>
                            @foreach (App\Models\Category::where('is_active', 1)->orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text font-size-11">Helps in organizing your library for coaches and seekers.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Choose File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="mediaFile" class="form-control" required>
                        <div id="fileHelp" class="form-text mt-2 font-size-12">
                            Max size: <strong>50MB</strong>. Supported: JPG, PNG, PDF, DOCX, MP4.
                        </div>
                    </div>

                    <div class="progress mt-3 d-none" id="uploadProgressContainer" style="height: 10px;">
                        <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary px-4 shadow">
                        <span id="btnText">Start Upload</span>
                        <span id="btnLoader" class="d-none spinner-border spinner-border-sm ms-1"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>