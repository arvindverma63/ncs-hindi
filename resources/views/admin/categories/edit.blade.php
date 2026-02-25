<x-app-layout title="Edit Category | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3">
                <h4 class="fs-18 fw-semibold m-0">Edit Category</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" id="editCategoryForm">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $category->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ old('slug', $category->slug) }}">
                                </div>

                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-medium">Icon Class</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i id="iconPreview" class="{{ $category->icon_class ?? 'mdi mdi-tag-outline' }} fs-18 text-primary"></i>
                                        </span>
                                        <input type="text" name="icon_class" id="icon_class" class="form-control"
                                            value="{{ old('icon_class', $category->icon_class) }}" 
                                            placeholder="Search icons..." autocomplete="off">
                                    </div>
                                    
                                    <div id="iconDropdown" class="dropdown-menu shadow-lg w-100 mt-1 border-0" 
                                         style="max-height: 280px; display: none; overflow-y: auto; z-index: 1050; border: 1px solid #eee !important;">
                                        <div id="iconList" class="d-flex flex-wrap gap-2 p-3">
                                            </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Searching local <code>assets/icons.json</code></small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="activeSwitch" {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activeSwitch">Is Active?</label>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Update Category</button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light px-4">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let allIcons = [];
                const iconInput = $('#icon_class');
                const iconDropdown = $('#iconDropdown');
                const iconList = $('#iconList');
                const iconPreview = $('#iconPreview');

                // 1. Fetch Local JSON
                fetch("{{ asset('assets/icons.json') }}")
                    .then(response => response.json())
                    .then(data => {
                        allIcons = data.i; // Map to "i" key from your JSON structure
                    })
                    .catch(err => console.error('Error loading icons.json:', err));

                // 2. Search Logic targeting "n" property
                iconInput.on('input', function() {
                    const query = $(this).val().toLowerCase().replace('mdi-', '').trim();
                    iconList.empty();

                    if (query.length < 2) {
                        iconDropdown.hide();
                        return;
                    }

                    const filtered = allIcons.filter(icon => icon.n.includes(query)).slice(0, 48);

                    if (filtered.length > 0) {
                        filtered.forEach(icon => {
                            const fullClass = `mdi mdi-${icon.n}`;
                            iconList.append(`
                                <button type="button" class="btn btn-outline-light border p-0 d-flex align-items-center justify-content-center icon-select-btn" 
                                        data-class="${fullClass}" title="${icon.n}" style="width: 42px; height: 42px;">
                                    <i class="${fullClass} fs-20 text-dark"></i>
                                </button>
                            `);
                        });
                        iconDropdown.show();
                    } else {
                        iconList.append('<div class="p-3 w-100 text-center text-muted">No matching icons found.</div>');
                        iconDropdown.show();
                    }
                });

                // 3. Selection Logic
                $(document).on('click', '.icon-select-btn', function() {
                    const selectedClass = $(this).data('class');
                    iconInput.val(selectedClass);
                    iconPreview.attr('class', selectedClass + ' fs-18 text-primary');
                    iconDropdown.hide();
                });

                // 4. Manual typing preview
                iconInput.on('blur', function() {
                    const val = $(this).val();
                    iconPreview.attr('class', val + ' fs-18 text-primary');
                });

                // Close dropdown on outside click
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.position-relative').length) {
                        iconDropdown.hide();
                    }
                });

                // Slug Generator
                $('#name').on('keyup', function() {
                    let name = $(this).val();
                    let slug = name.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                    $('#slug').val(slug);
                });
            });
        </script>
    @endpush
</x-app-layout>

<style>
    .icon-select-btn:hover {
        background-color: #f8f9fa !important;
        border-color: #4b49ac !important;
        transform: scale(1.1);
    }
    #iconDropdown::-webkit-scrollbar { width: 5px; }
    #iconDropdown::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .fs-20 { font-size: 20px; }
</style>