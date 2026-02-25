<x-coach-layout title="My Messages | BestBusinessCoach">
    <div class="container-fluid">
        <div class="py-3 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fs-20 fw-bold m-0 text-dark">Active Conversations</h4>
                <p class="text-muted font-size-13 mb-0">Manage your communication with connected seekers.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Seeker</th>
                                        <th>Last Message</th>
                                        <th>Status</th>
                                        <th>Last Activity</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($interactions as $interaction)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $interaction->seeker->profile_image && file_exists(public_path($interaction->seeker->profile_image)) ? asset($interaction->seeker->profile_image) : asset('assets/images/users/user.avif') }}"
                                                        class="rounded-circle border"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 fw-bold text-dark">
                                                            {{ $interaction->seeker->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $interaction->seeker->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted text-truncate d-inline-block"
                                                    style="max-width: 250px;">
                                                    {{ Str::limit($interaction->message, 45) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($interaction->status === 'sent')
                                                    <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                        <i class="mdi mdi-email-outline me-1"></i> New Message
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary px-2 py-1">
                                                        <i class="mdi mdi-email-open-outline me-1"></i> Read
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $interaction->created_at->diffForHumans() }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('coach.interactions.chat', $interaction->seeker_id) }}"
                                                    class="btn btn-sm btn-primary px-3 shadow-sm">
                                                    View Chat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="avatar-lg bg-soft-light text-muted rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                    style="width: 70px; height: 70px;">
                                                    <iconify-icon icon="tabler:message-off"
                                                        class="display-6"></iconify-icon>
                                                </div>
                                                <h5 class="text-dark fw-bold">No active conversations yet.</h5>
                                                <p class="text-muted">Once you accept a connection request, you can
                                                    start messaging here.</p>
                                                <a href="{{ route('coach.requests.index') }}"
                                                    class="btn btn-sm btn-outline-primary">Check Requests</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    {{ $interactions->links() }}
                </div>
            </div>
        </div>
    </div>

</x-coach-layout>

<style>
    .bg-soft-danger {
        background-color: rgba(234, 84, 85, 0.12) !important;
    }

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.12) !important;
    }
</style>
