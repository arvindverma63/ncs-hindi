<x-coach-layout title="{{ isset($blog) ? 'Edit Blog' : 'Create New Blog' }}">
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .mt-10 {
                margin-top: 10px !important;
            }

            .content-wrapper {
                overflow-y: auto;
                min-height: 100vh;
                padding-bottom: 2rem;
            }

            /* Compact Select2 */
            .select2-container .select2-selection--single {
                height: 35px !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 6px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 33px !important;
                font-size: 13px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 33px !important;
            }

            /* Compact Quill */
            #editor-container {
                height: 400px;
                font-size: 14px;
                border-bottom-left-radius: 6px;
                border-bottom-right-radius: 6px;
            }

            .ql-toolbar {
                padding: 4px !important;
                border-top-left-radius: 6px;
                border-top-right-radius: 6px;
            }

            .card-header {
                padding: 0.75rem 1rem !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 0.3rem;
            }

            .sticky-publish {
                position: sticky;
                top: 80px;
            }
        </style>
    @endpush

    <div class="content-wrapper px-2 mt-10">
        <form action="{{ isset($blog) ? route('coach.blogs.update', $blog->id) : route('coach.blogs.store') }}"
            method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            @if (isset($blog))
                @method('PUT')
            @endif

            <div class="row g-3"> {{-- Reduced gutter spacing --}}
                <div class="col-lg-9"> {{-- Wider main area for better focus --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Blog Title</label>
                                <input type="text" name="title"
                                    class="form-control form-control-sm @error('title') is-invalid @enderror"
                                    value="{{ old('title', $blog->title ?? '') }}" placeholder="Enter title...">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <input type="hidden" name="content" id="contentInput">
                                <div id="editor-container">{!! old('content', $blog->content ?? '') !!}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Compact SEO Metadata --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-2">
                            <h6 class="card-title mb-0 fs-14 fw-bold text-muted">SEO Metadata (Optional)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control form-control-sm"
                                        value="{{ old('meta_title', $blog->meta_title ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <textarea name="meta_description" class="form-control form-control-sm" rows="1">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="sticky-publish">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="card-title mb-0 fw-bold fs-14">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Category</label>
                                    <select name="category_id" class="form-select select2-js">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cover Image</label>
                                    <div class="border rounded p-1 text-center bg-light">
                                        <img src="{{ asset($blog->featured_image ?? 'assets/images/placeholder-img.png') }}"
                                            id="blog-preview" class="img-fluid rounded mb-2"
                                            style="max-height: 120px; width: 100%; object-fit: cover;">
                                        <input type="file" name="featured_image" class="form-control form-control-sm"
                                            onchange="previewBlogImg(this)">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                    <iconify-icon icon="tabler:circle-check" class="me-1 align-middle"></iconify-icon>
                                    {{ isset($blog) ? 'Update' : 'Publish' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2-js').select2({
                    width: '100%'
                });
                const quill = new Quill('#editor-container', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                'header': [1, 2, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });
                $('#blogForm').on('submit', function() {
                    var content = quill.root.innerHTML;
                    $('#contentInput').val(content === '<p><br></p>' ? '' : content);
                });
            });

            function previewBlogImg(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#blog-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
</x-coach-layout>
