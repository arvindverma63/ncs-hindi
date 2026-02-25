<x-app-layout title="Edit Post | BestBusinessCoachIndia">
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            #quill-editor {
                height: 400px;
                background: white;
            }

            .ql-toolbar.ql-snow {
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                background: #f8f9fa;
            }

            .ql-container.ql-snow {
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }

            .image-preview-container {
                width: 100%;
                height: 200px;
                border: 2px dashed #dee2e6;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background-color: #f8f9fa;
                margin-top: 10px;
            }

            .image-preview-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: cover;
            }

            .is-invalid-quill {
                border: 1px solid #dc3545 !important;
                border-radius: 5px;
            }
        </style>
    @endpush

    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <h4 class="fs-18 fw-semibold m-0">Edit Article</h4>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>

            <form id="blogForm" action="{{ route('admin.blogs.update', $blog->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Blog Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title', $blog->title) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <input type="hidden" name="content" id="content-input">
                                    <div id="quill-wrapper">
                                        <div id="quill-editor">{!! old('content', $blog->content) !!}</div>
                                    </div>
                                    <span id="content-error" class="text-danger small d-none">Please enter content for
                                        your blog.</span>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">SEO Metadata</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $blog->meta_title) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select select2">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ $blog->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control"
                                        accept="image/*">
                                    <div class="image-preview-container">
                                        @php
                                            $imagePath = $blog->featured_image;
                                            $displayUrl =
                                                $imagePath && Storage::disk('public')->exists($imagePath)
                                                    ? asset('storage/' . $imagePath)
                                                    : 'https://placehold.co/600x400?text=No+Image';
                                        @endphp
                                        <img id="img-preview" src="{{ $displayUrl }}" alt="Preview">
                                    </div>
                                    <small class="text-muted d-block mt-1">Leave blank to keep current image.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Publishing Status</label>
                                    <select name="is_published" class="form-select">
                                        <option value="1" {{ $blog->is_published ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="0" {{ !$blog->is_published ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-medium">Update
                                    Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%'
                });

                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                'header': [1, 2, 3, 4, 5, 6, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            ['blockquote', 'code-block'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });

                $('#featured_image').change(function() {
                    const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#img-preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                });

                $("#blogForm").validate({
                    rules: {
                        title: "required",
                        category_id: "required"
                    },
                    messages: {
                        title: "Enter a title",
                        category_id: "Select a category"
                    },
                    errorElement: "span",
                    errorPlacement: function(error, element) {
                        error.addClass("invalid-feedback");
                        element.closest(".mb-3").append(error);
                    },
                    highlight: function(element) {
                        $(element).addClass("is-invalid");
                    },
                    unhighlight: function(element) {
                        $(element).removeClass("is-invalid");
                    },
                    submitHandler: function(form) {
                        document.querySelector('#content-input').value = quill.root.innerHTML;

                        if (quill.getText().trim().length === 0 && quill.root.innerHTML.indexOf('<img') ===
                            -1) {
                            $('#quill-wrapper').addClass('is-invalid-quill');
                            $('#content-error').removeClass('d-none');
                            return false;
                        }
                        form.submit();
                    }
                });

                quill.on('text-change', function() {
                    $('#quill-wrapper').removeClass('is-invalid-quill');
                    $('#content-error').addClass('d-none');
                });
            });
        </script>
    @endpush
</x-app-layout>
