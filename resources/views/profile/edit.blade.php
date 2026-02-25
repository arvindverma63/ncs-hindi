<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // --- Helper: Clear errors on typing ---
            $('input').on('input change', function() {
                if ($(this).val() !== '') {
                    // Remove Tailwind error classes
                    $(this).removeClass('border-red-500 focus:border-red-500 ring-red-500');
                    // Remove the error message text
                    $(this).next('.validation-error').remove();
                }
            });

            // --- 1. Validate Profile Info Form ---
            $('#profileUpdateForm').on('submit', function(e) {
                let isValid = true;

                // Reset visual errors
                $('.validation-error').remove();
                $('input').removeClass('border-red-500 focus:border-red-500 ring-red-500');

                // Validate Name
                let name = $(this).find('input[name="name"]');
                if (name.val().trim() === '') {
                    showError(name, 'Name is required.');
                    isValid = false;
                }

                // Validate Email
                let email = $(this).find('input[name="email"]');
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email.val().trim() === '') {
                    showError(email, 'Email is required.');
                    isValid = false;
                } else if (!emailRegex.test(email.val())) {
                    showError(email, 'Please enter a valid email address.');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    $('html, body').animate({
                        scrollTop: $(".border-red-500").first().offset().top - 150
                    }, 500);
                }
            });

            // --- 2. Validate Password Update Form ---
            $('#passwordUpdateForm').on('submit', function(e) {
                let isValid = true;

                // Reset visual errors
                $('.validation-error').remove();
                $('input').removeClass('border-red-500 focus:border-red-500 ring-red-500');

                // Current Password
                let current = $('#update_password_current_password');
                if (current.val().trim() === '') {
                    showError(current, 'Current password is required.');
                    isValid = false;
                }

                // New Password
                let newPass = $('#update_password_password');
                if (newPass.val().trim() === '') {
                    showError(newPass, 'New password is required.');
                    isValid = false;
                } else if (newPass.val().length < 8) {
                    showError(newPass, 'Password must be at least 8 characters.');
                    isValid = false;
                }

                // Confirm Password
                let confirm = $('#update_password_password_confirmation');
                if (confirm.val().trim() === '') {
                    showError(confirm, 'Please confirm your password.');
                    isValid = false;
                } else if (confirm.val() !== newPass.val()) {
                    showError(confirm, 'Passwords do not match.');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $(".border-red-500").first().offset().top - 150
                    }, 500);
                }
            });

            // --- Helper: Show Error Message ---
            function showError(element, message) {
                // Add Tailwind red border
                element.addClass('border-red-500 focus:border-red-500 ring-red-500');
                // Append error message
                element.after('<p class="text-sm text-red-600 mt-2 validation-error">' + message + '</p>');
            }
        });
    </script>

</x-app-layout>
