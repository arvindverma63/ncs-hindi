<x-app-layout title="Connection Logs | Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3">
                <h4 class="fs-18 fw-bold m-0">Global Connection Requests</h4>
                <p class="text-muted font-size-13">Monitor and manage handshakes between seekers and coaches.</p>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sender (Seeker)</th>
                                            <th>Receiver (Coach)</th>
                                            <th>Introduction</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-end px-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $req)
                                        <tr>
                                            <td>
                                                <span class="fw-medium text-dark">{{ $req->sender->name }}</span>
                                                <br><small class="text-muted">{{ $req->sender->email }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-medium text-dark">{{ $req->receiver->name }}</span>
                                                <br><small class="text-muted">{{ $req->receiver->email }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;" title="{{ $req->message }}">
                                                    {{ $req->message ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($req->status == 'accepted')
                                                    <span class="badge bg-soft-success text-success">Accepted</span>
                                                @elseif($req->status == 'pending')
                                                    <span class="badge bg-soft-warning text-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ $req->created_at->format('d M, Y H:i') }}</td>
                                            <td class="text-end px-3">
                                                <form action="{{ route('admin.requests.destroy', $req->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-soft-danger confirm-delete">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($requests->hasPages())
                        <div class="card-footer bg-white border-top-0">
                            {{ $requests->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>