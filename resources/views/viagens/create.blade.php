<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Cadastrar Viagem</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Viagem</h1>

        <div class="card p-4 mt-3">
            <form id="viagemForm">

                {{-- Seleção de Motoristas --}}
                <div class="mb-3">
                    <label class="form-label">Selecione os Motoristas</label>
                    <div class="border p-3 rounded">
                        @foreach($motoristasDisponiveis as $motorista)
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" class="motorista-checkbox me-2" value="{{ $motorista->id }}">
                                <label class="me-2">{{ $motorista->nome }}</label>
                                <select class="form-control form-control-sm tipo-motorista" disabled>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Principal">Principal</option>
                                    <option value="Auxiliar">Auxiliar</option>
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Seleção do Veículo --}}
                <div class="mb-3">
                    <label for="veiculo" class="form-label">Veículo</label>
                    <select class="form-control" id="veiculo" name="veiculo_id" required>
                        <option value="">Selecione um veículo</option>
                        @foreach($veiculosDisponiveis as $veiculo)
                            <option  value="{{ $veiculo->id }}" data-km="{{ $veiculo->km_atual }}">
                                {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Quilometragem Atual --}}
                <div class="mb-3">
                    <label for="km_inicio" class="form-label">Quilometragem Atual</label>
                    <input type="number" class="form-control" id="km_inicio" name="km_inicio" required readonly>
                </div>

                {{-- Datas de Início e Fim --}}
                <div class="mb-3">
                    <label for="data_hora_inicio" class="form-label">Data e Hora de Início</label>
                    <input type="datetime-local" class="form-control" id="data_hora_inicio" name="data_hora_inicio" required>
                </div>

                <div class="mb-3">
                    <label for="data_hora_fim" class="form-label">Data e Hora de Fim</label>
                    <input type="datetime-local" class="form-control" id="data_hora_fim" name="data_hora_fim" required>
                </div>

                {{-- Botões --}}
                <button type="submit" class="btn btn-success">Cadastrar</button>
                <a href="{{ route('viagens.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/viagens.js') }}"></script>

    {{-- Script para habilitar a seleção de tipo de motorista --}}
    <script>
        document.querySelectorAll(".motorista-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                let tipoSelect = this.nextElementSibling.nextElementSibling; // O select ao lado do checkbox
                tipoSelect.disabled = !this.checked;
                if (!this.checked) {
                    tipoSelect.value = "";
                }
            });
        });
    </script>

</body>

</html>
