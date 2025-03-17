<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Editar Motorista</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Motorista: {{ $motorista->nome }}</h1>

        <div class="card p-4 mt-3">
            <form id="motoristaForm">
                <input type="hidden" id="motorista_id" value="{{ $motorista->id }}">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required value="{{ $motorista->nome }}">
                </div>

                <div class="mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required 
                        value="{{$motorista->data_nascimento }}">
                </div>

                <div class="mb-3">
                    <label for="cnh" class="form-label">CNH</label>
                    <input type="text" class="form-control" id="cnh" maxlength="11" name="cnh" required value="{{ $motorista->cnh }}">
                </div>

                <button type="submit" class="btn btn-success">Editar</button>
                <a href="{{ route('motoristas.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/motoristas.js') }}"></script>


</body>
</html>
