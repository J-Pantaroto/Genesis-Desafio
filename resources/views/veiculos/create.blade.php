<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Cadastrar Veiculo</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Veiculo</h1>

        <div class="card p-4 mt-3">
            <form id="veiculoForm">

                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                </div>

                <div class="mb-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="ano" name="ano" required>
                </div>

                <div class="mb-3">
                    <label for="data_aquisicao" class="form-label">Data de Aquisição</label>
                    <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao" required>
                </div>

                <div class="mb-3">
                    <label for="km_aquisicao" class="form-label">Quilometragem de Aquisição</label>
                    <input type="number" class="form-control" id="km_aquisicao" name="km_aquisicao" required>
                </div>
                <div class="mb-3">
                    <label for="km_atual" class="form-label">Quilometragem Atual</label>
                    <input type="number" class="form-control" id="km_atual" name="km_atual" required>
                </div>
                <div class="mb-3">
                    <label for="renavam" class="form-label">Renavam</label>
                    <input type="text" class="form-control" maxlength="11" id="renavam" name="renavam" required>
                </div>
                <div class="mb-3">
                    <label for="placa" class="form-label">Placa</label>
                    <input type="text" class="form-control" id="placa" maxlength="7" name="placa" required>
                </div>
                <button type="submit" class="btn btn-success">Cadastrar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/veiculos.js') }}"></script>

</body>

</html>
