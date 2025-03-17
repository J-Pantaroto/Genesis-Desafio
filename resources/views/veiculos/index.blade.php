<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Veiculos</h1>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('veiculos.create') }}" class="btn btn-primary">Novo veiculos</a>
        </div>

        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Ano</th>
                    <th scope="col">Data de aquisição</th>
                    <th scope="col">KM de aquisição</th>
                    <th scope="col">KM Atual</th>
                    <th scope="col">Renavam</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Ações</th>
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
                            <th scope="row">{{ $veiculo->id }}</th>
                            <td>{{ $veiculo->modelo }}</td>
                            <td>{{ $veiculo->ano }}</td>
                            <td>{{ $veiculo->data_aquisicao }}</td>
                            <td>{{ $veiculo->km_aquisicao }}</td>
                            <td>{{ $veiculo->km_atual }}</td>
                            <td>{{ $veiculo->renavam }}</td>
                            <td>{{ $veiculo->placa }}</td>
                            <td>
                                <a href="{{ route('veiculos.edit', $veiculo->id) }}"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $veiculo->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/veiculos.js') }}"></script>
</x-main-layout>
