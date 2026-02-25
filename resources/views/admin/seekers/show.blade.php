<x-app-layout title="Seeker Profile | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3">
                <h4 class="fs-18 fw-semibold m-0">Seeker Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.seekers.index') }}">Seekers</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-xl mx-auto mb-4">
                                <span class="avatar-title bg-soft-secondary text-secondary fs-24 rounded-circle">
                                    {{ strtoupper(substr($seeker->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $seeker->user->name }}</h5>
                            <p class="text-muted">{{ $seeker->user->email }}</p>

                            <div class="d-flex justify-content-center gap-2 mb-3">
                                @if ($seeker->is_verified)
                                    <span class="badge bg-success"><i class="mdi mdi-check"></i> Verified Account</span>
                                @else
                                    <span class="badge bg-warning text-dark">Unverified</span>
                                @endif
                            </div>

                            <div class="text-start mt-4">
                                <h5 class="fs-13 text-uppercase text-muted mb-3">About</h5>
                                <p class="mb-2"><strong>Phone:</strong> {{ $seeker->user->phone ?? 'Not provided' }}
                                </p>
                                <p class="mb-2"><strong>City:</strong> {{ $seeker->city ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>State:</strong> {{ $seeker->state ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Company:</strong> {{ $seeker->company_name ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Domain:</strong> {{ $seeker->business_domain ?? 'General' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Update Status</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.seekers.update', $seeker->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Verification Status</label>
                                        <select name="is_verified" class="form-select">
                                            <option value="1" {{ $seeker->is_verified ? 'selected' : '' }}>
                                                Verified</option>
                                            <option value="0" {{ !$seeker->is_verified ? 'selected' : '' }}>
                                                Unverified</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Business Domain</label>
                                        <input type="text" name="business_domain" class="form-control"
                                            value="{{ $seeker->business_domain }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
