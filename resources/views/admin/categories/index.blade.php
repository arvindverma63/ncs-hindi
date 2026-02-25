<x-app-layout title="Categories | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <h4 class="fs-18 fw-semibold m-0">Business Categories</h4>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus me-1"></i> Add Category
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">Icon</th>
                                            <th>Category Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $category)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($category->icon_class)
                                                        <i class="{{ $category->icon_class }} fs-20 text-muted"></i>
                                                    @else
                                                        <i class="mdi mdi-folder-outline fs-20 text-muted"></i>
                                                    @endif
                                                </td>
                                                <td class="fw-medium text-dark">{{ $category->name }}</td>
                                                <td class="text-muted">{{ $category->slug }}</td>
                                                <td>
                                                    @if ($category->is_active)
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                        class="btn btn-sm btn-soft-info">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.categories.destroy', $category->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger"
                                                            onclick="return confirm('Are you sure? removing this category will detach it from all coaches.')">
                                                            <i class="mdi mdi-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">No categories
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
