<x-seeker-layout title="Edit Profile | BestBusinessCoach">
    <div class="container-fluid">
        <div class="py-3">
            <h4 class="fs-20 fw-bold m-0 text-dark">My Profile Settings</h4>
            <p class="text-muted font-size-13">Update your personal information and business preferences.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="position-relative">
                                    <img id="imagePreview"
                                        src="{{ $user->profile_image ? asset($user->profile_image) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                        class="rounded-circle border p-1"
                                        style="width: 100px; height: 100px; object-fit: cover;">

                                    <label for="profile_image"
                                        class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; cursor: pointer; border: 3px solid #fff;">
                                        <i class="mdi mdi-camera font-size-16"></i>
                                    </label>
                                    <input type="file" name="profile_image" id="profile_image" class="d-none"
                                        accept="image/*">
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fs-16">Profile Photo</h5>
                                    <p class="text-muted font-size-13 mb-0">Update your avatar. Recommended size:
                                        256x256.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" class="form-control bg-light" value="{{ $user->email }}"
                                        readonly>
                                    <small class="text-muted">Email cannot be changed.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Phone Number</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}" placeholder="e.g. +91 9999999999">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title mb-0">Business & Professional Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Business Domain / Industry</label>
                                    <select name="business_domain" class="form-select">
                                        <option value="">Select Domain</option>
                                        <option value="Real Estate"
                                            {{ old('business_domain', $profile->business_domain ?? '') == 'Real Estate' ? 'selected' : '' }}>
                                            Real Estate</option>
                                        <option value="Technology"
                                            {{ old('business_domain', $profile->business_domain ?? '') == 'Technology' ? 'selected' : '' }}>
                                            Technology</option>
                                        <option value="Retail"
                                            {{ old('business_domain', $profile->business_domain ?? '') == 'Retail' ? 'selected' : '' }}>
                                            Retail</option>
                                        <option value="Healthcare"
                                            {{ old('business_domain', $profile->business_domain ?? '') == 'Healthcare' ? 'selected' : '' }}>
                                            Healthcare</option>
                                        <option value="Finance"
                                            {{ old('business_domain', $profile->business_domain ?? '') == 'Finance' ? 'selected' : '' }}>
                                            Finance</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                        value="{{ old('company_name', $profile->company_name ?? '') }}"
                                        placeholder="e.g. Verma Solutions">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">City</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city', $profile->city ?? '') }}" placeholder="e.g. Kanpur">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">State</label>
                                    <input type="text" name="state" class="form-control"
                                        value="{{ old('state', $profile->state ?? '') }}"
                                        placeholder="e.g. Uttar Pradesh">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mb-5">
                        <a href="{{ route('seeker.dashboard') }}" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Profile Changes</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h6 class="text-white fw-bold mb-3"><i class="mdi mdi-information-outline me-1"></i> Why
                            complete your profile?</h6>
                        <ul class="font-size-13 ps-3 mb-0" style="list-style-type: circle;">
                            <li class="mb-2">Coaches are 80% more likely to accept requests from completed
                                profiles.</li>
                            <li class="mb-2">Your industry and city help us recommend the best local mentors.
                            </li>
                            <li>Verified seekers get priority access to premium blog content.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('profile_image').onchange = function(evt) {
                const [file] = this.files;
                if (file) {
                    document.getElementById('imagePreview').src = URL.createObjectURL(file);
                }
            }
        </script>
    @endpush
</x-seeker-layout>
