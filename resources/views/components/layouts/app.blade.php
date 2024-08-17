<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
        <title>{{ $title ?? 'Page Title' }}</title>
        @yield('styles')
    </head>
    <body>
    @yield('content')
    @yield('script')
    </body>
</html>
