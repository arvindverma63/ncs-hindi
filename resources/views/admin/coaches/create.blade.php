<x-app-layout title="Add Coach | BestBusinessCoachIndia">
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Optional: Fix Select2 height to match Bootstrap inputs */
            .select2-container .select2-selection--single {
                height: 38px;
                line-height: 38px;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #556ee6;
                border: none;
                color: white;
            }
        </style>
    @endpush

    <div class="content">
        <div class="container-fluid">
            <div class="py-3">
                <h4 class="fs-18 fw-semibold m-0">Onboard New Coach</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.coaches.index') }}">Coaches</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="coachForm" action="{{ route('admin.coaches.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-account-circle me-1"></i>
                                    1. Login Details</h5>
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
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', $coach->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ old('gender', $coach->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ old('gender', $coach->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label d-block">Show Personal Details on Profile?</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="show_personal_details"
                                                value="1" id="showDetails"
                                                {{ old('show_personal_details', $coach->show_personal_details ?? 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showDetails">Visible to Seekers</label>
                                        </div>
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

                                <h5 class="mb-3 mt-4 text-uppercase bg-light p-2"><i class="mdi mdi-briefcase me-1"></i>
                                    2. Professional Profile</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Designation / Title</label>
                                        <input type="text" name="designation" class="form-control"
                                            placeholder="e.g. Senior Business Consultant"
                                            value="{{ old('designation') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control"
                                            value="{{ old('company_name') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Experience (Years)</label>
                                        <input type="number" name="experience_years" class="form-control"
                                            value="{{ old('experience_years') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">LinkedIn URL</label>
                                        <input type="url" name="linkedin_url" class="form-control"
                                            placeholder="https://linkedin.com/in/..."
                                            value="{{ old('linkedin_url') }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Expertise Categories</label>
                                        <select name="categories[]" class="form-control select2" multiple="multiple"
                                            data-placeholder="Select Categories...">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Bio / Achievements</label>
                                        <textarea name="bio" class="form-control" rows="4" placeholder="Tell us why this coach is famous...">{{ old('bio') }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Create Coach Account</button>
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // 1. Initialize Select2
                $('.select2').select2({
                    width: '100%',
                    hideSelected: true
                });

                // 2. Real-time validation cleanup
                $('input, select, textarea').on('input change', function() {
                    if ($(this).val() !== '') {
                        $(this).removeClass('is-invalid');
                        $(this).next('.error-text').remove();
                    }
                });

                // Select2 specific change event to clear errors
                $('.select2').on('change', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-text').remove();
                });

                // 3. Form Validation
                $('#coachForm').on('submit', function(e) {
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
                        showError(email, 'Email Address is required.');
                        isValid = false;
                    } else if (!emailPattern.test(email.val())) {
                        showError(email, 'Please enter a valid email address.');
                        isValid = false;
                    }

                    // Password
                    let password = $('#password');
                    if (password.val().length < 8) {
                        showError(password, 'Password must be at least 8 characters.');
                        isValid = false;
                    }

                    // Confirm Password
                    let confirmPassword = $('#password_confirmation');
                    if (confirmPassword.val() === '') {
                        showError(confirmPassword, 'Please confirm the password.');
                        isValid = false;
                    } else if (confirmPassword.val() !== password.val()) {
                        showError(confirmPassword, 'Passwords do not match.');
                        isValid = false;
                    }

                    // City
                    let city = $('input[name="city"]');
                    if (city.val().trim() === '') {
                        showError(city, 'City is required.');
                        isValid = false;
                    }

                    // Categories (Optional: Force at least one selection)
                    /* let categories = $('select[name="categories[]"]');
                    if (categories.val().length === 0) {
                        // For Select2, error placement is a bit different, usually after the span container
                        categories.next('.select2-container').after('<span class="text-danger error-text small mt-1 d-block">Please select at least one category.</span>');
                        isValid = false;
                    }
                    */

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
