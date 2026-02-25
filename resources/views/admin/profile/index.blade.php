<x-app-layout title="My Profile | Fitx Admin">
    <div class="content">
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">My Profile</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img id="headerProfileImage"
                                        src="{{ $user->profile_image ?: 'https://ui-avatars.com/api/?name=' . $user->name }}"
                                        class="rounded-circle avatar-xl img-thumbnail border border-4 border-white"
                                        alt="profile-image" style="width: 100px; height: 100px; object-fit: cover;">

                                    <label for="profile_upload"
                                        class="position-absolute bottom-0 end-0 bg-light rounded-circle p-1 shadow-sm border border-2 border-white"
                                        style="cursor: pointer; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-camera text-dark"></i>
                                    </label>
                                </div>

                                <div class="ms-3">
                                    <h3 class="m-0 text-dark fw-bold">{{ $user->name }}</h3>
                                    <p class="text-muted mb-0 fs-14">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="adminProfileForm" method="POST" action="{{ route('admin.profile.update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <input type="file" id="profile_upload" name="profile" class="d-none" accept="image/*"
                                    onchange="previewImage(event)">

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $user->name) }}" placeholder="Enter Name">
                                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Contact Phone</label>
                                    <input type="text" id="phoneInput" class="form-control" name="phone"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Enter Phone"
                                        maxlength="10">
                                    <x-input-error class="mt-1" :messages="$errors->get('phone')" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="Enter Email">
                                    <x-input-error class="mt-1" :messages="$errors->get('email')" />
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form id="adminPasswordForm" method="post" action="{{ route('admin.password.update') }}">
                                @csrf
                                @method('put')

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Old Password</label>
                                    <input type="password" id="current_password" class="form-control"
                                        name="current_password" placeholder="Enter current password">
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">New Password</label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Enter new password">
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Confirm Password</label>
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" placeholder="Confirm new password">
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- <div class="mt-3 text-end">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="mdi mdi-delete me-1"></i> Delete Account
                        </button>
                    </div> --}}
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="{{ route('admin.profile.destroy') }}" class="modal-content">
                @csrf
                @method('delete')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white">Delete Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to confirm you would like to permanently delete your account.</p>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // 1. Image Preview Logic
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('headerProfileImage');
                    output.src = reader.result;
                };
                if (event.target.files[0]) {
                    // Optional: Check size > 2MB
                    if (event.target.files[0].size > 2097152) {
                        alert("Image too large (Max 2MB)");
                        event.target.value = "";
                        return;
                    }
                    reader.readAsDataURL(event.target.files[0]);

                    // Optional: Auto submit form on image change? 
                    // Uncomment line below if you want instant upload
                    // $('#adminProfileForm').submit(); 
                }
            }

            $(document).ready(function() {
                // 2. Phone Restriction
                $('#phoneInput').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                // 3. Clear errors on typing
                $('input').on('input change', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-text').remove();
                });

                // 4. Profile Form Validation
                $('#adminProfileForm').on('submit', function(e) {
                    let isValid = true;
                    $('.error-text').remove();
                    $('.form-control').removeClass('is-invalid');

                    // Name
                    let name = $('input[name="name"]');
                    if (name.val().trim() === '') {
                        showError(name, 'Name is required.');
                        isValid = false;
                    }

                    // Phone
                    let phone = $('input[name="phone"]');
                    if (phone.val().trim() !== '' && phone.val().length !== 10) {
                        showError(phone, 'Phone must be 10 digits.');
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

                    if (!isValid) {
                        e.preventDefault();
                        // Scroll to error
                        $('html, body').animate({
                            scrollTop: $(".is-invalid").first().offset().top - 100
                        }, 500);
                    }
                });

                // 5. Password Form Validation
                $('#adminPasswordForm').on('submit', function(e) {
                    let isValid = true;
                    $('.error-text').remove();
                    $('.form-control').removeClass('is-invalid');

                    let current = $('#current_password');
                    let newPass = $('#password');
                    let confirm = $('#password_confirmation');

                    if (current.val().trim() === '') {
                        showError(current, 'Current password required.');
                        isValid = false;
                    }

                    if (newPass.val().trim() === '') {
                        showError(newPass, 'New password required.');
                        isValid = false;
                    } else if (newPass.val().length < 8) {
                        showError(newPass, 'Min 8 characters required.');
                        isValid = false;
                    }

                    if (confirm.val().trim() === '') {
                        showError(confirm, 'Confirm your password.');
                        isValid = false;
                    } else if (confirm.val() !== newPass.val()) {
                        showError(confirm, 'Passwords do not match.');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });

                function showError(element, message) {
                    element.addClass('is-invalid');
                    element.after('<div class="text-danger error-text small mt-1">' + message + '</div>');
                }
            });
        </script>
    @endpush
</x-app-layout>
