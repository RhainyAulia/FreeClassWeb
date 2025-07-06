<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PW-IBIK</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" ></script> --}}
    <link rel="stylesheet" href="{{ url('./assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('./assets/bootstrap-icons/font/bootstrap-icons.css') }}">
</head>
<body class="bg-body-tertiary">
    <header>
        @extends('templates.headers.navigation')
    </header>
    <main class="container mt-5 mb-5">
        @yield('content')
    </main>
    <footer class="container mt-5">
       <p class="fs-7">Copyright &copy; 2023</p>
    </footer>
    <script src="{{ url('./assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>