<x-seeker-layout title="Seeker Dashboard | BestBusinessCoach">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box py-3">
                    <h4 class="fs-20 fw-bold m-0 text-dark">Welcome back, {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-0">Manage your coaching requests and communications here.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body" style="background: brown;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 text-uppercase fs-12 fw-bold">Active Connections</h6>
                                <h2 class="mb-0 text-white">{{ $stats['active_connections'] }}</h2>
                            </div>
                            <iconify-icon icon="tabler:link" class="fs-1 opacity-50"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted text-uppercase fs-12 fw-bold">Total Requests Sent</h6>
                                <h2 class="mb-0 text-dark">{{ $stats['sent_requests'] }}</h2>
                            </div>
                            <iconify-icon icon="tabler:send" class="fs-1 text-info opacity-25"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted text-uppercase fs-12 fw-bold">Unread Messages</h6>
                                <h2 class="mb-0 text-dark">{{ $stats['unread_messages'] }}</h2>
                            </div>
                            <iconify-icon icon="tabler:message-dots"
                                class="fs-1 text-warning opacity-25"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="card-title mb-0">Recent Connection Requests</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Coach Name</th>
                                        <th>Status</th>
                                        <th>Sent On</th>
                                        <th class="text-end px-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRequests as $req)
                                        <tr>
                                            <td class="fw-medium text-dark">{{ $req->receiver->name }}</td>
                                            <td>
                                                @if ($req->status == 'accepted')
                                                    <span
                                                        class="badge bg-soft-success text-success px-2 py-1">Accepted</span>
                                                @elseif($req->status == 'pending')
                                                    <span
                                                        class="badge bg-soft-warning text-warning px-2 py-1">Pending</span>
                                                @else
                                                    <span
                                                        class="badge bg-soft-danger text-danger px-2 py-1">Declined</span>
                                                @endif
                                            </td>
                                            <td>{{ $req->created_at->format('d M, Y') }}</td>
                                            <td class="text-end px-3">
                                                @if ($req->status == 'accepted')
                                                    <a href="{{ route('seeker.messaging.chat',$req->receiver->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-message-text-outline me-1"></i> Message
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-light disabled">View Profile</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">You haven't sent any
                                                requests yet.</td>
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
</x-seeker-layout>

<style>
    .bg-soft-success {
        background-color: rgba(40, 199, 111, 0.12) !important;
    }

    .bg-soft-warning {
        background-color: rgba(255, 159, 67, 0.12) !important;
    }

    .bg-soft-danger {
        background-color: rgba(234, 84, 85, 0.12) !important;
    }
</style>
