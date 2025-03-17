<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Editar Veículo</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Veículo: {{ $veiculo->modelo }}</h1>

        <div class="card p-4 mt-3">
            <form id="veiculoForm">
                <input type="hidden" id="veiculo_id" value="{{ $veiculo->id }}">
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" required
                        value="{{ $veiculo->modelo }}">
                </div>

                <div class="mb-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="ano" name="ano" required
                        value="{{ $veiculo->ano }}" min="1900" max="{{ date('Y') }}">
                </div>

                <div class="mb-3">
                    <label for="data_aquisicao" class="form-label">Data de Aquisição</label>
                    <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao" required
                        value="{{ ($veiculo->data_aquisicao_iso) }}">
                </div>

                <div class="mb-3">
                    <label for="km_aquisicao" class="form-label">Quilometragem de Aquisição</label>
                    <input type="number" class="form-control" id="km_aquisicao" name="km_aquisicao" required readonly
                        value="{{ $veiculo->km_aquisicao }}">
                </div>

                <div class="mb-3">
                    <label for="km_atual" class="form-label">Quilometragem Atual</label>
                    <input type="number" class="form-control" id="km_atual" name="km_atual" required readonly
                        value="{{ $veiculo->km_atual }}">
                </div>

                <div class="mb-3">
                    <label for="renavam" class="form-label">Renavam</label>
                    <input type="text" class="form-control" id="renavam" name="renavam" required readonly
                        value="{{ $veiculo->renavam }}">
                </div>

                <div class="mb-3">
                    <label for="placa" class="form-label">Placa</label>
                    <input type="text" class="form-control" id="placa" name="placa" required readonly
                        value="{{ $veiculo->placa }}">
                </div>

                <button type="submit" class="btn btn-success">Editar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/veiculos.js') }}"></script>
</body>

</html>
