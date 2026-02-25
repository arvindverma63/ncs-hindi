@props(['title' => 'Fitx'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fitx Admin" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.common.styles-lib') 
    @stack('styles')
</head>

<body>
    
    {{ $slot }}

    @include('layouts.common.scripts-lib')

    <script>
        @if(session('success') || session('error') || $errors->any())
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 5000
            };

            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            // Also show validation errors as toasts if you prefer
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    @stack('scripts')
</body>
</html>