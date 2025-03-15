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
        <h1 class="text-center">Sistema de Viagens</h1>

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-center">Viagens do dia</h2>
            <a href="{{ route('viagens.create') }}" class="btn btn-primary">Nova Viagem</a>
        </div>

        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Motorista</th>
                    <th scope="col">Veículo</th>
                    <th scope="col">Início</th>
                    <th scope="col">Fim</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @if ($viagens->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma viagem para o dia de hoje!</td>
                    </tr>
                @else
                    @foreach ($viagens as $viagem)
                        <tr>
                            <th scope="row">{{ $viagem->id }}</th>
                            <td>{{ $viagem->motorista->nome }}</td>
                            <td>{{ $viagem->veiculo->modelo }}</td>
                            <td>{{ \Carbon\Carbon::parse($viagem->data_hora_inicio)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($viagem->data_hora_fim)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('viagens.edit', $viagem->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $viagem->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
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
                        fetch(`/viagens/${viagemId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Excluído!", data.success, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Erro!", data.error, "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Erro!", "Ocorreu um problema ao excluir a viagem.", "error");
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
