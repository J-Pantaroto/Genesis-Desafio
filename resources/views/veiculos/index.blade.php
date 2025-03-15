<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Sistema de Viagens</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('/viagens') }}">Viagens</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('/motoristas') }}">Motoristas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('/veiculos') }}">Veículos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-4">
        <h1 class="text-center">Veiculos</h1>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('viagens.create') }}" class="btn btn-primary">Novo veiculos</a>
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
    <script>
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function() {
                let viagemId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Tem certeza?",
                    text: "Essa ação não pode ser desfeita!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/veiculos/${veiculoId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Excluido", data.success, "success").then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire("Erro", data.error, "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Erro", "Ocorreu um problema ao excluir o veiculo",
                                    "error");
                            });
                    }
                });
            });
        });
    </script>

</body>

</html>
