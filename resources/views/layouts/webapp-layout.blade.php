<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.webapp.head')
</head>
<body class="flex h-screen overflow-hidden">

    @include('layouts.partials.webapp.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden bg-black">
       @include('layouts.partials.webapp.header')

        <main class="flex-1 overflow-y-auto p-6 lg:p-10 no-scrollbar space-y-12 pb-32">
            {{ $slot }}
        </main>
    </div>

    @include('layouts.partials.webapp.footer')
    @include('layouts.partials.webapp.script')
</body>
</html>
