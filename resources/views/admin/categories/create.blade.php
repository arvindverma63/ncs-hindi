<x-app-layout title="Add Category | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Add New Category</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form id="createCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="e.g. Business Coaching" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Slug (Optional)</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ old('slug') }}" placeholder="auto-generated-slug">
                                </div>

                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-medium">Select Icon</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i id="iconPreview" class="mdi mdi-tag-outline fs-18 text-primary"></i>
                                        </span>
                                        <input type="text" name="icon_class" id="icon_class" class="form-control border-start-0" 
                                            placeholder="Search icons (e.g. briefcase, chart, lightbulb)..." autocomplete="off">
                                    </div>
                                    
                                    <div id="iconDropdown" class="dropdown-menu shadow-lg w-100 mt-1 border-0" 
                                         style="max-height: 280px; display: none; overflow-y: auto; z-index: 1050; border: 1px solid #eee !important;">
                                        <div id="iconList" class="d-flex flex-wrap gap-2 p-3">
                                            </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Searching locally from your <code>icons.json</code></small>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch" checked>
                                        <label class="form-check-label" for="activeSwitch">Category Status (Active)</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Category</button>
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

                // 1. Fetch Local JSON matching your structure
                fetch("{{ asset('assets/icons.json') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Accessing data.i based on your provided JSON structure
                        allIcons = data.i; 
                        console.log('MDI Data parsed successfully');
                    })
                    .catch(err => console.error('Error loading icons.json:', err));

                // 2. Search Logic targeting the "n" property
                iconInput.on('input', function() {
                    const query = $(this).val().toLowerCase().replace('mdi-', '').trim();
                    iconList.empty();

                    if (query.length < 2) {
                        iconDropdown.hide();
                        return;
                    }

                    // Filter based on "n" (name) property
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
                        iconList.append('<div class="p-3 w-100 text-center text-muted">No matching icons.</div>');
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

                // 4. Close dropdown
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.position-relative').length) {
                        iconDropdown.hide();
                    }
                });

                // Slug Helper
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
    .transition-all { transition: all 0.2s; }
    #iconDropdown::-webkit-scrollbar { width: 5px; }
    #iconDropdown::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
</style>