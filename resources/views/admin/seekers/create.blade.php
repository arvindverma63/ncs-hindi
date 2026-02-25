<x-app-layout title="Add Seeker | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3">
                <h4 class="fs-18 fw-semibold m-0">Add New Seeker</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.seekers.index') }}">Seekers</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="createSeekerForm" action="{{ route('admin.seekers.store') }}" method="POST">
                                @csrf

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-account me-1"></i> 1.
                                    Account Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phoneInput" class="form-control"
                                            value="{{ old('phone') }}" maxlength="10" placeholder="9876543210">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>

                                <h5 class="mb-3 mt-4 text-uppercase bg-light p-2"><i
                                        class="mdi mdi-card-account-details me-1"></i> 2. Business Profile</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control"
                                            value="{{ old('company_name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Business Domain / Interest</label>
                                        <input type="text" name="business_domain" class="form-control"
                                            placeholder="e.g. Real Estate, Startups"
                                            value="{{ old('business_domain') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state" class="form-control"
                                            value="{{ old('state') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Verification Status</label>
                                        <select name="is_verified" class="form-select">
                                            <option value="0">Unverified</option>
                                            <option value="1">Verified</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Create Seeker</button>
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
                $('#createSeekerForm').on('submit', function(e) {
                    let isValid = true;
                    $('.error-text').remove();
                    $('.form-control').removeClass('is-invalid');

                    // Name
                    let name = $('input[name="name"]');
                    if (name.val().trim() === '') {
                        showError(name, 'Full Name is required.');
                        isValid = false;
                    }

                    // Email
                    let email = $('input[name="email"]');
                    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (email.val().trim() === '') {
                        showError(email, 'Email is required.');
                        isValid = false;
                    } else if (!emailPattern.test(email.val())) {
                        showError(email, 'Invalid email format.');
                        isValid = false;
                    }

                    // Phone
                    let phone = $('#phoneInput');
                    if (phone.val().length !== 10) {
                        showError(phone, 'Phone must be 10 digits.');
                        isValid = false;
                    }

                    // Password
                    let password = $('#password');
                    if (password.val().length < 8) {
                        showError(password, 'Min 8 characters required.');
                        isValid = false;
                    }

                    // Confirm Password
                    let confirm = $('#password_confirmation');
                    if (confirm.val() !== password.val()) {
                        showError(confirm, 'Passwords do not match.');
                        isValid = false;
                    }

                    // City
                    let city = $('input[name="city"]');
                    if (city.val().trim() === '') {
                        showError(city, 'City is required.');
                        isValid = false;
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
