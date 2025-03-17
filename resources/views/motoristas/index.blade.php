<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Motoristas</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('motoristas.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Novo
                Motorista</a>
        </div>
        <div class="table-responsive">
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
                            <td colspan="5" class="text-center">Nenhum motorista cadastrado!</td>
                        </tr>
                    @else
                        @foreach ($motoristas as $motorista)
                            <tr>
                                <th class="text-center" id="motoristaId" scope="row">{{ $motorista->id }}</th>
                                <td class="text-center">{{ $motorista->nome }}</td>
                                <td class="text-center">{{ $motorista->data_nascimento }}</td>
                                <td class="text-center">{{ $motorista->cnh }}</td>
                                <td class="text-center">
                                    <a href="{{ route('motoristas.edit', $motorista->id) }}"
                                        class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>Editar</a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $motorista->id }}"><i
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
    <script src="{{ asset('js/motoristas.js') }}"></script>

</x-main-layout>
