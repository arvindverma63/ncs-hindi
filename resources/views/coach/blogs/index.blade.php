<x-coach-layout title="My Blogs">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold">Manage Blogs</h5>
                    <a href="{{ route('coach.blogs.create') }}" class="btn btn-primary btn-sm px-3">
                        <iconify-icon icon="tabler:plus" class="align-middle me-1"></iconify-icon> Create New
                    </a>
                </div>
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-4">Blog</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Created At</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ ($blog->featured_image && file_exists(public_path($blog->featured_image))) ? asset($blog->featured_image) : asset('assets/images/default-blog.png') }}" 
                                                    class="rounded me-2" 
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                <span
                                                    class="fw-medium text-dark">{{ Str::limit($blog->title, 40) }}</span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-light text-dark border">{{ $blog->category->name }}</span>
                                        </td>
                                        <td>
                                            @if ($blog->is_published)
                                                <span class="badge bg-success-subtle text-success">Published</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($blog->view_count ?? 0) }}</td>
                                        <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('coach.blogs.edit', $blog->id) }}"
                                                    class="btn btn-soft-info btn-sm">
                                                    <iconify-icon icon="tabler:edit"></iconify-icon>
                                                </a>
                                                <form action="{{ route('coach.blogs.destroy', $blog->id) }}"
                                                    method="POST" onsubmit="return confirm('Delete this Blog?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="tabler:trash"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No Blogs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($blogs->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-coach-layout>
