<x-app-layout title="Edit Seeker | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Edit Seeker Profile</h4>
                    <p class="mb-0 text-muted">User: {{ $seeker->user->name }}</p>
                </div>
                <div>
                    @if ($seeker->is_verified)
                        <span class="badge bg-success fs-14 px-3 py-2"><i class="mdi mdi-check-decagram"></i>
                            Verified</span>
                    @else
                        <span class="badge bg-warning text-dark fs-14 px-3 py-2">Unverified</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="editSeekerForm" action="{{ route('admin.seekers.update', $seeker->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-account-edit me-1"></i>
                                    Account Info</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $seeker->user->name) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email (Read Only)</label>
                                        <input type="email" class="form-control bg-light"
                                            value="{{ $seeker->user->email }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phoneInput" class="form-control"
                                            value="{{ old('phone', $seeker->user->phone) }}" maxlength="10">
                                    </div>
                                </div>

                                <h5 class="mb-3 mt-4 text-uppercase bg-light p-2"><i
                                        class="mdi mdi-briefcase-edit me-1"></i> Profile Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control"
                                            value="{{ old('company_name', $seeker->company_name) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Business Domain</label>
                                        <input type="text" name="business_domain" class="form-control"
                                            value="{{ old('business_domain', $seeker->business_domain) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city', $seeker->city) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state" class="form-control"
                                            value="{{ old('state', $seeker->state) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Verification Status</label>
                                        <select name="is_verified" class="form-select">
                                            <option value="1" {{ $seeker->is_verified ? 'selected' : '' }}>Verified
                                            </option>
                                            <option value="0" {{ !$seeker->is_verified ? 'selected' : '' }}>
                                                Unverified</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-lock-reset me-1"></i>
                                        Security</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password <small class="text-muted">(Leave blank to
                                                keep current)</small></label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                                    <a href="{{ route('admin.seekers.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Phone Restriction
                $('#phoneInput').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                // Clear errors
                $('input, select').on('input change', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-text').remove();
                });

                // Validation
                $('#editSeekerForm').on('submit', function(e) {
                    let isValid = true;
                    $('.error-text').remove();
                    $('.form-control').removeClass('is-invalid');

                    // Name
                    let name = $('input[name="name"]');
                    if (name.val().trim() === '') {
                        showError(name, 'Full Name is required.');
                        isValid = false;
                    }

                    // Phone
                    let phone = $('#phoneInput');
                    if (phone.val().length !== 10) {
                        showError(phone, 'Phone must be 10 digits.');
                        isValid = false;
                    }

                    // City
                    let city = $('input[name="city"]');
                    if (city.val().trim() === '') {
                        showError(city, 'City is required.');
                        isValid = false;
                    }

                    // Password (Optional, but matching required if entered)
                    let password = $('#password');
                    let confirm = $('#password_confirmation');

                    if (password.val() !== '') {
                        if (password.val().length < 8) {
                            showError(password, 'Min 8 characters required.');
                            isValid = false;
                        }
                        if (confirm.val() === '') {
                            showError(confirm, 'Please confirm password.');
                            isValid = false;
                        } else if (confirm.val() !== password.val()) {
                            showError(confirm, 'Passwords do not match.');
                            isValid = false;
                        }
                    }

                    if (!isValid) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: $(".is-invalid").first().offset().top - 100
                        }, 500);
                    }
                });

                function showError(element, message) {
                    element.addClass('is-invalid');
                    element.after('<span class="text-danger error-text small mt-1 d-block">' + message + '</span>');
                }
            });
        </script>
    @endpush
</x-app-layout>
