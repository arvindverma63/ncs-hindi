<x-app-layout title="Edit Role | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Edit Role</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                </div>

                                <h5 class="mb-3">Assign Permissions</h5>
                                <hr>

                                @foreach ($permissions as $group => $groupPermissions)
                                    @php
                                        $groupId = Str::slug($group);
                                        $totalInGroup = $groupPermissions->count();
                                        $assignedInGroup = $groupPermissions->filter(fn($p) => in_array($p->id, $assignedPermissions))->count();
                                        $isAllChecked = $totalInGroup === $assignedInGroup && $totalInGroup > 0;
                                    @endphp

                                    <div class="row mb-4 border-bottom pb-3">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input select-all" 
                                                       type="checkbox" 
                                                       id="group-{{ $groupId }}" 
                                                       data-group="{{ $groupId }}"
                                                       {{ $isAllChecked ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold text-uppercase text-primary" for="group-{{ $groupId }}">
                                                    {{ ucfirst($group) }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                @foreach ($groupPermissions as $permission)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="perm-{{ $permission->id }}"
                                                                   data-group="{{ $groupId }}"
                                                                   {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Update Role</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {

            $('.select-all').on('change', function() {
                var group = $(this).data('group');
                $('.permission-checkbox[data-group="'+group+'"]').prop('checked', this.checked);
            });

            $('.permission-checkbox').on('change', function() {
                var group = $(this).data('group');
                var total = $('.permission-checkbox[data-group="'+group+'"]').length;
                var checked = $('.permission-checkbox[data-group="'+group+'"]:checked').length;
                $('#group-'+group).prop('checked', total === checked);
            });
        });
    </script>
    @endpush
</x-app-layout>