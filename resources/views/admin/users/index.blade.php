<x-app-layout title="User Management | BestBusinessCoachIndia Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Users List</h4>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Add User
                    </a>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET" id="userFilterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search User</label>
                                <input type="text" name="search" class="form-control" placeholder="Name or Email..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="">All Roles</option>
                                    @foreach (Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->name }}"
                                            {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-filter-variant"></i> Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="text-danger small mt-2">{{ $errors->first() }}</div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User / Role</th>
                                            <th>Contact Info</th>
                                            <th>Status</th>
                                            <th>Joined Date</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            // Use Media Library URL or a Default Placeholder
                                                            $avatarUrl =
                                                                $user->profile_image ?:
                                                                'https://ui-avatars.com/api/?name=' .
                                                                    urlencode($user->name) .
                                                                    '&background=random';
                                                        @endphp
                                                        <img src="{{ $avatarUrl }}"
                                                            class="rounded-circle avatar-sm me-3" alt="user-avatar">

                                                        <div>
                                                            <h5 class="m-0 font-14">{{ $user->name }}</h5>
                                                            @if ($user->roles->isNotEmpty())
                                                                <span
                                                                    class="badge bg-primary-subtle text-primary font-12 mt-1">
                                                                    {{ ucfirst($user->roles->first()->name) }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-muted font-12 mt-1">No
                                                                    Role</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span><i class="mdi mdi-email-outline me-1 text-muted"></i>
                                                            {{ $user->email }}</span>
                                                        @if ($user->phone)
                                                            <span class="mt-1"><i
                                                                    class="mdi mdi-phone-outline me-1 text-muted"></i>
                                                                {{ $user->phone }}</span>
                                                        @else
                                                            <span class="mt-1 text-muted fs-12">-- No Phone --</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input status-toggle" type="checkbox"
                                                            role="switch" id="status_{{ $user->id }}"
                                                            data-id="{{ $user->id }}"
                                                            {{ $user->status ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="status_{{ $user->id }}">
                                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                                        </label>
                                                    </div>
                                                </td>

                                                <td>
                                                    {{ $user->created_at->format('d M Y') }} <br>
                                                    <small
                                                        class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                                                </td>

                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        {{-- Edit Button --}}
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="Edit">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>

                                                        {{-- Delete Form (SweetAlert) --}}
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST" class="delete-form d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-soft-danger"
                                                                title="Delete">
                                                                <i class="mdi mdi-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-account-off fs-24 d-block mb-2"></i>
                                                        No users found.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // 1. Handle Status Toggle via AJAX
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    let userId = this.getAttribute('data-id');
                    let status = this.checked ? 1 : 0;
                    let label = this.nextElementSibling; // The <label> tag

                    // Update UI immediately (Optimistic UI)
                    label.textContent = status ? 'Active' : 'Inactive';

                    // Send Request
                    fetch("{{ route('admin.users.update-status') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: userId,
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            toastr.success(data.message || 'Status updated successfully');
                        })
                        .catch(error => {
                            toastr.error('Something went wrong!');
                            // Revert checkbox if failed
                            this.checked = !status;
                            label.textContent = !status ? 'Active' : 'Inactive';
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
