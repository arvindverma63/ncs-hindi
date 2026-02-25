<x-coach-layout title="Profile Settings">
    <form action="{{ route('coach.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mt-3">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('assets/images/users/user-1.jpg') }}"
                                id="preview-img" class="rounded-circle shadow-sm border"
                                style="width: 130px; height: 130px; object-fit: cover;">
                            <label for="profile_image"
                                class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer shadow"
                                style="width: 35px; height: 35px;">
                                <iconify-icon icon="tabler:camera" class="fs-18"></iconify-icon>
                                <input type="file" name="profile_image" id="profile_image" class="d-none"
                                    onchange="previewImage(this)">
                            </label>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                        {{-- <p class="text-muted small">Coach ID: {{ substr(string: $user->id, 0, 8) }}</p> --}}
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="card-title mb-0 fw-bold">Expertise Categories</h6>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @php $selectedCats = $user->coachProfile->categories->pluck('id')->toArray(); @endphp
                        @foreach ($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    value="{{ $category->id }}" id="cat{{ $category->id }}"
                                    {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}>
                                <label class="form-check-label fs-13" for="cat{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                        @error('categories')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="card-title mb-0 fw-bold">Social Links</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control"
                                value="{{ old('linkedin_url', $user->coachProfile->linkedin_url ?? '') }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-medium">Website URL</label>
                            <input type="url" name="website_url" class="form-control"
                                value="{{ old('website_url', $user->coachProfile->website_url ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Professional Profile</h5>
                        <span
                            class="badge {{ ($user->coachProfile->approval_status ?? '') === 'approved' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-2">
                            {{ ucfirst($user->coachProfile->approval_status ?? 'Pending') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Full Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Company Name</label>
                                <input type="text" name="company_name" class="form-control"
                                    value="{{ old('company_name', $user->coachProfile->company_name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Designation</label>
                                <input type="text" name="designation" class="form-control"
                                    value="{{ old('designation', $user->coachProfile->designation ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="male"
                                        {{ old('gender', $user->coachProfile->gender ?? '') == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female"
                                        {{ old('gender', $user->coachProfile->gender ?? '') == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                    <option value="other"
                                        {{ old('gender', $user->coachProfile->gender ?? '') == 'other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control"
                                    value="{{ old('experience_years', $user->coachProfile->experience_years ?? '') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end pb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_personal_details"
                                        id="show_personal"
                                        {{ old('show_personal_details', $user->coachProfile->show_personal_details ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="show_personal">Show Personal
                                        Details</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Professional Bio</label>
                                <textarea name="bio" class="form-control" rows="6">{{ old('bio', $user->coachProfile->bio ?? '') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">City</label>
                                <input type="text" name="city" class="form-control"
                                    value="{{ old('city', $user->coachProfile->city ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">State</label>
                                <input type="text" name="state" class="form-control"
                                    value="{{ old('state', $user->coachProfile->state ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Country</label>
                                <input type="text" name="country" class="form-control"
                                    value="{{ old('country', $user->coachProfile->country ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Update Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-img').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
</x-coach-layout>

<style>
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
    }

    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
