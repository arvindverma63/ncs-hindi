<x-app-layout title="Create User | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Add New User</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-account-circle me-1"></i>
                                    Account Info</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}">
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}">
                                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                        <select name="role" id="role" class="form-select">
                                            <option value="" disabled selected>Select Role</option>
                                            @foreach ($roles as $roleName)
                                                <option value="{{ $roleName }}"
                                                    {{ old('role') == $roleName ? 'selected' : '' }}>
                                                    {{ ucfirst($roleName) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone (10 Digits) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phoneInput" class="form-control"
                                            value="{{ old('phone') }}" maxlength="10" placeholder="9876543210">
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Profile Image</label>
                                        <input type="file" name="profile_image" id="imageInput" class="form-control"
                                            accept="image/*" onchange="previewImage(event)">
                                        <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
                                        <small class="text-muted">Allowed: jpg, jpeg, png, webp. Max: 2MB</small>
                                    </div>

                                    <div class="col-md-6 mb-3 d-flex align-items-center">
                                        <div class="border p-1 rounded" style="display: none;" id="previewContainer">
                                            <img id="imagePreview" src="#" alt="Image Preview"
                                                style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary px-4">Create User</button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
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
            // 1. Image Preview (Global function for onchange attribute)
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('imagePreview');
                    var container = document.getElementById('previewContainer');
                    output.src = reader.result;
                    container.style.display = 'block';
                };
                if (event.target.files[0]) {
                    if (event.target.files[0].size > 2097152) {
                        alert("File is too big! Please select an image under 2MB.");
                        event.target.value = "";
                        return;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            $(document).ready(function() {
                // 2. Phone Restriction (jQuery)
                $('#phoneInput').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                // 3. Clear errors on typing
                $('input, select').on('input change', function() {
                    if ($(this).val() !== '') {
                        $(this).removeClass('is-invalid');
                        $(this).next('.error-text').remove();
                    }
                });

                // 4. Form Validation
                $('#createUserForm').on('submit', function(e) {
                    let isValid = true;

                    // Clear previous errors
                    $('.error-text').remove();
                    $('.form-control, .form-select').removeClass('is-invalid');

                    // --- Validate Name ---
                    let name = $('#name');
                    if (name.val().trim() === '') {
                        showError(name, 'Full Name is required.');
                        isValid = false;
                    }

                    // --- Validate Email ---
                    let email = $('#email');
                    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (email.val().trim() === '') {
                        showError(email, 'Email Address is required.');
                        isValid = false;
                    } else if (!emailPattern.test(email.val())) {
                        showError(email, 'Enter a valid email address.');
                        isValid = false;
                    }

                    // --- Validate Role ---
                    let role = $('#role');
                    if (!role.val()) {
                        showError(role, 'Please select a role.');
                        isValid = false;
                    }

                    // --- Validate Phone ---
                    let phone = $('#phoneInput');
                    if (phone.val().trim() === '') {
                        showError(phone, 'Phone number is required.');
                        isValid = false;
                    } else if (phone.val().length !== 10) {
                        showError(phone, 'Phone number must be exactly 10 digits.');
                        isValid = false;
                    }

                    // --- Validate Password ---
                    let password = $('#password');
                    if (password.val().length < 8) {
                        showError(password, 'Password must be at least 8 characters.');
                        isValid = false;
                    }

                    // --- Validate Confirm Password ---
                    let confirm = $('#password_confirmation');
                    if (confirm.val() === '') {
                        showError(confirm, 'Please confirm your password.');
                        isValid = false;
                    } else if (confirm.val() !== password.val()) {
                        showError(confirm, 'Passwords do not match.');
                        isValid = false;
                    }

                    // --- If Invalid, Stop & Scroll ---
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
