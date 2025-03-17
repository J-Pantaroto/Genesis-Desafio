<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Veiculos</h1>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('veiculos.create') }}" class="btn btn-primary">Novo veiculos</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Modelo</th>
                        <th class="text-center" scope="col">Ano</th>
                        <th class="text-center" scope="col">Data de aquisição</th>
                        <th class="text-center" scope="col">KM de aquisição</th>
                        <th class="text-center" scope="col">KM Atual</th>
                        <th class="text-center" scope="col">Renavam</th>
                        <th class="text-center" scope="col">Placa</th>
                        <th class="text-center" scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($veiculos->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">Nenhum veiculo cadastrado</td>
                        </tr>
                    @else
                        @foreach ($veiculos as $veiculo)
                            <tr>
                                <th class="text-center" scope="row">{{ $veiculo->id }}</th>
                                <td class="text-center">{{ $veiculo->modelo }}</td>
                                <td class="text-center">{{ $veiculo->ano }}</td>
                                <td class="text-center">{{ $veiculo->data_aquisicao }}</td>
                                <td class="text-center">{{ $veiculo->km_aquisicao }}</td>
                                <td class="text-center">{{ $veiculo->km_atual }}</td>
                                <td class="text-center">{{ $veiculo->renavam }}</td>
                                <td class="text-center">{{ $veiculo->placa }}</td>
                                <td class="text-center">
                                    <a href="{{ route('veiculos.edit', $veiculo->id) }}"
                                        class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>Editar</a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $veiculo->id }}"><i
                                            class="fa-solid fa-trash fa-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/veiculos.js') }}"></script>
</x-main-layout>
