<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Motoristas</h1>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('motoristas.create') }}" class="btn btn-primary">Novo Motorista</a>
        </div>

        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" scope="col">ID</th>
                    <th class="text-center" scope="col">Nome</th>
                    <th class="text-center" scope="col">Data de nascimento</th>
                    <th class="text-center" scope="col">CNH</th>
                    <th class="text-center" scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @if ($motoristas->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Nenhum motorista cadastrado!</td>
                    </tr>
                @else
                    @foreach ($motoristas as $motorista)
                        <tr>
                            <th id="motoristaId" scope="row">{{ $motorista->id }}</th>
                            <td>{{ $motorista->nome }}</td>
                            <td>{{ $motorista->data_nascimento }}</td>
                            <td>{{ $motorista->cnh }}</td>
                            <td>
                                <a href="{{ route('motoristas.edit', $motorista->id) }}"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $motorista->id }}">Excluir</button>
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
    <script src="{{ asset('js/motoristas.js') }}"></script>

</x-main-layout>
