<x-app-layout title="Blog Management | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Manage Blogs</h4>
                    <p class="text-muted mb-0">Create and manage your website articles.</p>
                </div>
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary d-flex align-items-center">
                    <iconify-icon icon="tabler:plus" class="fs-18 me-1"></iconify-icon> Add New Post
                </a>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.blogs.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Search Title</label>
                                <input type="text" name="search" class="form-control" placeholder="Article title..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Publish Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Articles</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2 px-4">Filter</button>
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-soft-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0 m-0">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap table-hover">
                                    <thead class="bg-light-subtle">
                                        <tr>
                                            <th class="ps-3" style="width: 80px;">Image</th>
                                            <th>Title & Category</th>
                                            <th>Author</th>
                                            <th>Views</th>
                                            <th>Status</th>
                                            <th class="text-end pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($blogs as $blog)
                                            <tr>
                                                <td class="ps-3">
                                                    @if ($blog->featured_image)
                                                        <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                                            class="rounded shadow-sm"
                                                            style="width: 60px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <img src="https://placehold.co/60x40?text=No+Img"
                                                            class="rounded border"
                                                            style="width: 60px; height: 40px; object-fit: cover;">
                                                    @endif
                                                </td>
                                                <td>
                                                    <h5 class="m-0 fs-14 fw-semibold text-dark">
                                                        {{ Str::limit($blog->title, 50) }}</h5>
                                                    <span
                                                        class="badge bg-soft-primary text-primary border-primary-subtle mt-1">
                                                        {{ $blog->category->name ?? 'Uncategorized' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fs-13 fw-medium">{{ $blog->author->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><iconify-icon icon="tabler:eye"
                                                            class="me-1 align-middle"></iconify-icon>{{ number_format($blog->view_count) }}</span>
                                                </td>
                                                <td>
                                                    @can('blogs.status')
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input status-toggle" type="checkbox"
                                                                data-id="{{ $blog->id }}"
                                                                {{ $blog->is_published ? 'checked' : '' }}>
                                                        </div>
                                                    @else
                                                        <span
                                                            class="badge {{ $blog->is_published ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $blog->is_published ? 'Published' : 'Draft' }}
                                                        </span>
                                                    @endcan
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="Edit Post">
                                                            <iconify-icon icon="tabler:edit"></iconify-icon>
                                                        </a>
                                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-soft-danger"
                                                                onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')"
                                                                title="Delete Post">
                                                                <iconify-icon icon="tabler:trash"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <iconify-icon icon="tabler:news-off"
                                                            class="fs-48 mb-2"></iconify-icon>
                                                        <p class="fs-16">No blog posts found matching your criteria.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($blogs->hasPages())
                                <div class="card-footer bg-white border-top">
                                    {{ $blogs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.status-toggle').on('change', function() {
                    const id = $(this).data('id');
                    const status = $(this).prop('checked') ? 1 : 0;

                    $.post("{{ route('admin.blogs.update-status') }}", {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            status: status
                        })
                        .done(function(res) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(res.message || 'Status updated successfully');
                            } else {
                                alert(res.message);
                            }
                        })
                        .fail(function() {
                            alert('Something went wrong. Please try again.');
                            // Revert the toggle if request fails
                            $(this).prop('checked', !status);
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
