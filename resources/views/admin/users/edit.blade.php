<x-app-layout title="Edit User | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Edit User</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="d-flex align-items-center mb-4 bg-light p-3 rounded">
                                    <div class="position-relative">
                                        <img id="headerPreview"
                                            src="{{ $user->profile_image ?: 'https://ui-avatars.com/api/?name=' . $user->name }}"
                                            class="rounded-circle avatar-lg img-thumbnail me-3"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <h5 class="m-0">{{ $user->name }}</h5>
                                        <p class="text-muted mb-0">Member since {{ $user->created_at->format('M Y') }}
                                        </p>
                                    </div>
                                </div>

                                <h5 class="mb-3 text-uppercase bg-light p-2">Account Info</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $user->name) }}">
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $user->email) }}">
                                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                        <select name="role" id="role" class="form-select">
                                            <option value="" disabled>Select Role</option>
                                            @foreach ($roles as $roleName)
                                                <option value="{{ $roleName }}"
                                                    {{ old('role', $userRole ?? '') == $roleName ? 'selected' : '' }}>
                                                    {{ ucfirst($roleName) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phoneInput" class="form-control"
                                            value="{{ old('phone', $user->phone) }}" maxlength="10">
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Update Profile Image</label>
                                        <input type="file" name="profile_image" id="imageInput" class="form-control"
                                            accept="image/*" onchange="previewImage(event)">
                                        <x-input-error :messages="$errors->get('profile_image')" class="mt-1" />
                                    </div>

                                    <div class="col-md-6 mb-3 d-flex align-items-center">
                                        <div class="border p-1 rounded" style="display: none;" id="previewContainer">
                                            <p class="text-muted font-12 mb-1">New Image Preview:</p>
                                            <img id="imagePreview" src="#" alt="Preview"
                                                style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;">
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <h5 class="mb-3 text-uppercase bg-light p-2"><i
                                                class="mdi mdi-lock-reset me-1"></i> Security</h5>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password <small class="text-muted">(Leave blank to
                                                keep current)</small></label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary px-4">Update User</button>
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
            // 1. Image Preview (Updates Header & Small Box)
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('imagePreview');
                    var container = document.getElementById('previewContainer');

                    output.src = reader.result;
                    container.style.display = 'block';

                    // Also update the big header image instantly
                    document.getElementById('headerPreview').src = reader.result;
                };
                if (event.target.files[0]) {
                    if (event.target.files[0].size > 2097152) {
                        alert("File is too big! Max 2MB.");
                        event.target.value = "";
                        return;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            $(document).ready(function() {
                // 2. Phone Restriction
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
                $('#editUserForm').on('submit', function(e) {
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

                    // --- Validate Password (Only if entered) ---
                    let password = $('#password');
                    let confirm = $('#password_confirmation');

                    if (password.val() !== '') {
                        if (password.val().length < 8) {
                            showError(password, 'Password must be at least 8 characters.');
                            isValid = false;
                        }
                        if (confirm.val() === '') {
                            showError(confirm, 'Please confirm the new password.');
                            isValid = false;
                        } else if (confirm.val() !== password.val()) {
                            showError(confirm, 'Passwords do not match.');
                            isValid = false;
                        }
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
