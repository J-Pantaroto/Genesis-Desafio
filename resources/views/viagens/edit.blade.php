<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <title>Editar Viagem</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Viagem</h1>

        <div class="card p-4 mt-3">
            <form id="viagemForm">

                <div class="mb-3">
                    <label for="motorista" class="form-label">Motorista</label>
                    <select class="form-control" id="motorista" name="motorista_id" required>
                        <option value="">Selecione um motorista</option>
                        @foreach ($motoristasDisponiveis as $motorista)
                            <option value="{{ $motorista->id }}"
                                {{ $viagem->motorista_id == $motorista->id ? 'selected' : '' }}>
                                {{ $motorista->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="motorista2" class="form-label">Motorista 2 (Opcional)</label>
                    <select class="form-control" id="motorista2" name="motorista_id_2">
                        <option value="">Selecione um segundo motorista</option>
                        @foreach ($motoristasDisponiveis as $motorista)
                            <option value="{{ $motorista->id }}" 
                                {{ $viagem->motorista_id_2 == $motorista->id ? 'selected' : '' }}>
                                {{ $motorista->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="veiculo" class="form-label">Veículo</label>
                    <select class="form-control" id="veiculo" name="veiculo_id" required>
                        <option value="">Selecione um veículo</option>
                        @foreach ($veiculosDisponiveis as $veiculo)
                            <option value="{{ $veiculo->id }}" data-km="{{ $veiculo->km_atual }}"
                                {{ $viagem->veiculo_id == $veiculo->id ? 'selected' : '' }}>
                                {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="km_inicio" class="form-label">Quilometragem Inicial</label>
                    <input type="number" class="form-control" id="km_inicio" name="km_inicio" required readonly
                        value="{{ $viagem->km_inicio }}">
                </div>

                <div class="mb-3">
                    <label for="data_hora_inicio" class="form-label">Data e Hora de Início</label>
                    <input type="datetime-local" class="form-control" id="data_hora_inicio" name="data_hora_inicio"
                        required value="{{ $viagem->data_hora_inicio_iso }}">
                </div>

                <div class="mb-3">
                    <label for="data_hora_fim" class="form-label">Data e Hora de Fim</label>
                    <input type="datetime-local" class="form-control" id="data_hora_fim" name="data_hora_fim" required
                        value="{{ $viagem->data_hora_fim_iso }}">
                </div>

                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <a href="{{ route('viagens.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/viagens.js') }}"></script>


</body>

</html>
