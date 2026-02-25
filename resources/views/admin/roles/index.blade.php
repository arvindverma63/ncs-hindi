<x-app-layout title="Roles List | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Roles Management</h4></div>
                <div class="text-end">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Add Role
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <table class="table table-centered mb-0 table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Role Name</th>
                                        <th>Permissions Count</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $index => $role)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span class="badge bg-primary-subtle text-primary fs-12">{{ ucfirst($role->name) }}</span></td>
                                            <td>{{ $role->permissions->count() }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-light border">
                                                    <i class="mdi mdi-pencil text-primary"></i>
                                                </a>

                                                <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-light border" onclick="confirmDelete('{{ $role->id }}')">
                                                        <i class="mdi mdi-delete text-danger"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>