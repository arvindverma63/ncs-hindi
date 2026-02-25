<x-seeker-layout title="Find Coaches | BestBusinessCoach">
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h4 class="fs-20 fw-bold m-0 text-dark">Discover Mentors</h4>
                <p class="text-muted font-size-13 mb-0">Browse through our verified business coaches to start your
                    journey.</p>
            </div>
            <div class="col-md-6">
                <form action="{{ route('seeker.coaches.index') }}" method="GET" class="mt-3 mt-md-0">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="mdi mdi-magnify"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Search by name or expertise..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary px-4">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($coaches as $coach)
                @php
                    // Check if a request already exists between seeker and this coach
                    $existingReq = \App\Models\MessageRequest::where('sender_id', auth()->id())
                        ->where('receiver_id', $coach->id)
                        ->first();
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 transition-hover">
                        <div class="card-body text-center p-4">
                            <div class="mb-3 position-relative d-inline-block">
                                <img src="{{ $coach->profile_image && file_exists(public_path($coach->profile_image)) ? asset($coach->profile_image) : asset('assets/images/users/user.avif') }}"
                                    class="rounded-circle border p-1"
                                    style="width: 90px; height: 90px; object-fit: cover;" alt="{{ $coach->name }}">

                                <span
                                    class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1"
                                    title="Verified Coach">
                                    <i class="mdi mdi-check-decagram text-white font-size-12"></i>
                                </span>
                            </div>

                            <h5 class="fs-16 fw-bold mb-1">{{ $coach->name }}</h5>
                            <p class="text-muted font-size-13 mb-3">{{ $coach->email }}</p>

                            <div class="d-flex flex-wrap justify-content-center gap-1 mb-4">
                                <span class="badge bg-soft-info text-info px-2 py-1">Business Strategy</span>
                                <span class="badge bg-soft-secondary text-secondary px-2 py-1">Marketing</span>
                            </div>

                            {{-- Conditional Button Logic --}}
                            @if (!$existingReq)
                                <button type="button" class="btn btn-primary w-100 shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#connectModal{{ $coach->id }}">
                                    <i class="mdi mdi-account-plus-outline me-1"></i> Connect Now
                                </button>
                            @elseif($existingReq->status == 'pending')
                                <button type="button" class="btn btn-soft-warning w-100 shadow-sm" disabled>
                                    <i class="mdi mdi-clock-outline me-1"></i> Request Sent
                                </button>
                            @elseif($existingReq->status == 'accepted')
                                <a href="{{ route('seeker.messaging.chat', $coach->id) }}"
                                    class="btn btn-success w-100 shadow-sm">
                                    <i class="mdi mdi-message-text-outline me-1"></i> Send Message
                                </a>
                            @else
                                <button type="button" class="btn btn-soft-danger w-100 shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#connectModal{{ $coach->id }}">
                                    <i class="mdi mdi-refresh me-1"></i> Try Again
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal for Connection --}}
                @if (!$existingReq || $existingReq->status == 'rejected')
                    <div class="modal fade" id="connectModal{{ $coach->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <form action="{{ route('seeker.coaches.connect', $coach->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Send Connection Request</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="text-center mb-4">
                                            <img src="{{ $coach->profile_image && file_exists(public_path($coach->profile_image)) ? asset($coach->profile_image) : asset('assets/images/users/user.avif') }}"
                                                class="rounded-circle mb-2"
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                alt="{{ $coach->name }}">

                                            <p class="text-muted font-size-14">You are requesting to message
                                                <strong>{{ $coach->name }}</strong>
                                            </p>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-medium text-dark">Introduction Note</label>
                                            <textarea name="message" class="form-control" rows="4"
                                                placeholder="Briefly describe your goals or why you'd like to connect..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light px-4"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary px-4">Send Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center py-5 mt-4">
                    <h4 class="fw-bold">No Coaches Found</h4>
                    <a href="{{ route('seeker.coaches.index') }}" class="btn btn-outline-primary px-4">Clear
                        Filters</a>
                </div>
            @endforelse
        </div>

        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                {{ $coaches->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</x-seeker-layout>
