@props(['title' => 'Fitx Admin'])
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fitx Admin Panel" name="description" />

    @include('layouts.common.styles-lib')
    @stack('styles-lib')

    {{-- ADDED THIS: This allows your blog page CSS to appear here --}}
    @stack('css')
    @stack('styles')

    <style>
        /* FIX FOR SCROLLING: Ensure the main content areas can grow */
        #app-layout {
            display: flex;
            min-height: 100vh;
        }

        .content-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: visible !important;
            /* Allow natural scrolling */
        }

        .content {
            flex: 1;
            padding: 20px;
            /* Base padding */
        }
    </style>
</head>

<body data-menu-color="light" data-sidebar="default">

    <div id="app-layout">

        @include('layouts.partials.seeker.header')

        @include('layouts.partials.seeker.sidebar')

        <div class="content-page">
            <div class="content">

                {{ $slot }}

            </div>

            @include('layouts.partials.seeker.footer')

        </div>
    </div>

    @include('layouts.common.scripts-lib')
    @stack('scripts-lib')

    {{-- VALIDATION & TOAST SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>

    <script>
        @if (session('success') || session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 5000
            };
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('submit', function(e) {
                if (e.target.classList.contains('delete-form')) {
                    e.preventDefault();
                    const form = e.target;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>

    {{-- YOUR BLOG SCRIPTS WILL APPEAR HERE --}}
    @stack('scripts')

</body>

</html>
