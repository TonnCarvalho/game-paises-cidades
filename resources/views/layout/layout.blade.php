<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('pageTitle')</title>
        <!-- favicon -->
        <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
        <!-- bootstrap -->
        <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
        <!-- css -->
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    </head>

    <body>

        <!-- main logo -->
        @include('layout.parts.logo')

        <!-- form -->
        <div class="container mt-3">
            @yield('content')
        </div>

        <!-- footer -->
        @include('layout.parts.footer')

        <!-- bootstrap -->
        <script src=" {{ asset('assets/bootstrap/bootstrap.bundle.min.js') }} "></script>

    </body>

    </html>
