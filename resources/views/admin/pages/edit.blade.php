<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            #quill-editor {
                height: 400px;
                background: white;
            }
            /* Styling to match Bootstrap's invalid state if needed */
            .is-invalid + .ql-container {
                border-color: #dc3545 !important;
            }
        </style>
    @endpush

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">{{ ucfirst(str_replace('_', ' ', $type)) }} Page Settings</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ ucfirst(str_replace('_', ' ', $type)) }} Page</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-top-0">
                <div class="card-header border-bottom-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            Update Content for: <span class="text-primary">{{ $currentLanguage->title ?? 'Default' }}</span>
                        </h5>

                        @if ($page)
                            <span class="badge bg-success bg-opacity-10 text-success">Editing Existing Translation</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">Creating New Translation</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.pages.update', $type) }}" method="POST" id="page-form" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-12 mt-3">
                                <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Page Title" id="title" name="title" value="{{ old('title', $page->title ?? '') }}" required>
                                <div class="invalid-feedback">Page Title is required</div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Page Content <span class="text-danger">*</span></label>
                                
                                <div id="quill-editor">{!! old('description', $page->description ?? '') !!}</div>
                                
                                <input type="hidden" name="description" id="description-input">
                                
                                <div class="invalid-feedback d-block" id="quill-error" style="display: none;">Page Content is required</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        
        <script>
            $(document).ready(function() {
                // Full Toolbar Configuration
                var toolbarOptions = [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                    [{ 'direction': 'rtl' }],                         // text direction
                    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                    [{ 'align': [] }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']                                         // remove formatting button
                ];

                // Initialize Quill
                var quill = new Quill('#quill-editor', {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow'
                });

                // Form submission handling
                var form = document.getElementById('page-form');
                form.onsubmit = function() {
                    // Populate hidden input with HTML content
                    var description = document.querySelector('input[id=description-input]');
                    description.value = quill.root.innerHTML;

                    // Basic client-side validation for Quill content
                    if (quill.getText().trim().length === 0) {
                        $('#quill-error').show();
                        return false;
                    } else {
                        $('#quill-error').hide();
                    }
                    
                    return true;
                };
            });
        </script>
    @endpush
</x-app-layout>