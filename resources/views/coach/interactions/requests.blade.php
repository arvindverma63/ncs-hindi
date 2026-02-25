<x-coach-layout title="Connection Requests | CoffeePass">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h4 class="fs-18 fw-bold m-0 text-dark">Connection Requests</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 font-size-12">
                                <li class="breadcrumb-item"><a href="#">Coach</a></li>
                                <li class="breadcrumb-item active">Requests</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0">Pending Connections</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Seeker</th>
                                        <th>Message</th>
                                        <th>Date Received</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if ($request->sender->profile_image)
                                                            <img src="{{ asset($request->sender->profile_image) }}"
                                                                class="rounded-circle avatar-xs" alt="user">
                                                        @else
                                                            <div
                                                                class="avatar-xs d-flex align-items-center justify-content-center bg-soft-primary rounded-circle">
                                                                <span
                                                                    class="text-primary fw-bold">{{ substr($request->sender->name, 0, 1) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0 font-size-14 text-dark">
                                                            {{ $request->sender->name }}</h6>
                                                        <small class="text-muted">{{ $request->sender->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted text-truncate d-inline-block"
                                                    style="max-width: 200px;" title="{{ $request->message }}">
                                                    {{ $request->message ?? 'No introductory message.' }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('d M, Y') }}</td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <form
                                                        action="{{ route('coach.requests.update', [$request->id, 'accepted']) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-soft-success px-3">
                                                            <i class="mdi mdi-check-bold me-1"></i> Accept
                                                        </button>
                                                    </form>

                                                    <form
                                                        action="{{ route('coach.requests.update', [$request->id, 'rejected']) }}"
                                                        method="POST" class="delete-form">
                                                        @csrf @method('PATCH')
                                                        <button type="button"
                                                            class="btn btn-sm btn-soft-danger px-3 confirm-reject">
                                                            <i class="mdi mdi-close-thick me-1"></i> Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="avatar-md bg-soft-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px;">
                                                    <iconify-icon icon="tabler:user-plus"
                                                        class="fs-2 text-muted"></iconify-icon>
                                                </div>
                                                <h5 class="text-muted">No pending connection requests.</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $requests->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body" style="background: brown;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="text-white-50 font-size-14 mb-2">Total Connections</h5>
                                <h2 class="mb-0 text-white">{{ $acceptedCount ?? 0 }}</h2>
                            </div>
                            <div
                                class="avatar-sm bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-account-group fs-3 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rejectBtns = document.querySelectorAll('.confirm-reject');
                rejectBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const form = this.closest('form');
                        Swal.fire({
                            title: 'Decline Request?',
                            text: "This user will not be able to message you.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, decline',
                            confirmButtonColor: '#ff4747',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
</x-coach-layout>

<style>
    .btn-soft-success {
        background-color: rgba(40, 199, 111, 0.12);
        color: #28c76f;
        border: none;
    }

    .btn-soft-success:hover {
        background-color: #28c76f;
        color: #fff;
    }

    .btn-soft-danger {
        background-color: rgba(234, 84, 85, 0.12);
        color: #ea5455;
        border: none;
    }

    .btn-soft-danger:hover {
        background-color: #ea5455;
        color: #fff;
    }
</style>
