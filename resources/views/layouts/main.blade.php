<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    @if (Route::currentRouteName() === 'home')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @elseif (Route::currentRouteName() === 'viagens.index')
        <link rel="stylesheet" href="{{ asset('css/viagens.css') }}">
    @endif
    <title>Sistema de Viagens</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/"><i class="fa-solid fa-truck-fast" style="color: #0e1520;"></i></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="{{ url('/viagens') }}">Viagens</a></li>
                        <li class="nav-item"><a class="nav-link active" href="{{ url('/motoristas') }}">Motoristas</a>
                        </li>
                        <li class="nav-item"><a class="nav-link active" href="{{ url('/veiculos') }}">Veículos</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        {{ $slot }}
    </main>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
