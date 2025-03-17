<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Viagens</h1>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('viagens.create') }}" class="btn btn-primary">Nova Viagem</a>
        </div>

        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" scope="col">ID</th>
                    <th class="text-center" scope="col">Motorista</th>
                    <th class="text-center"scope="col">Veículo</th>
                    <th class="text-center" scope="col">Início</th>
                    <th class="text-center" scope="col">Chegada</th>
                    <th class="text-center" scope="col">KM de Saida</th>
                    <th class="text-center" scope="col">KM de Chegada</th>
                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @if ($viagens->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma viagem cadastrada!</td>
                    </tr>
                @else
                    @foreach ($viagens as $viagem)
                        <tr>
                            <th scope="row">{{ $viagem->id }}</th>
                            <td>
                                @isset($viagem->motorista)
                                    {{ $viagem->motorista->nome }}
                                @else
                                    A definir
                                @endisset
                            </td>
                            <td>
                                @isset($viagem->veiculo)
                                    {{ $viagem->veiculo->modelo }}
                                @else
                                    A definir
                                @endisset
                            </td>
                            <td>{{ $viagem->data_hora_inicio }}</td>
                            <td>{{ $viagem->data_hora_fim }}</td>
                            <td>{{ $viagem->km_inicio }}</td>
                            <td>{{ $viagem->km_fim }}</td>
                            <td>{{ $viagem->status }}</td>
                            <td>
                                @if ($viagem->status === 'AGUARDANDO INICIO')
                                    <button class="btn btn-success btn-sm iniciar-btn m-1"
                                        data-id="{{ $viagem->id }}">Iniciar</button>
                                @elseif($viagem->status === 'EM ANDAMENTO')
                                    <button class="btn btn-primary btn-sm finalizar-btn m-1"
                                        data-id="{{ $viagem->id }}">Finalizar</button>
                                @endif
                                <a href="{{ route('viagens.edit', $viagem->id) }}"
                                    class="btn btn-warning btn-sm m-1">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn m-1"
                                    data-id="{{ $viagem->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/viagens.js') }}"></script>
    <script>
                //iniciar e finalizar
                document.querySelectorAll(".iniciar-btn").forEach(button => {
            button.addEventListener("click", function() {
                let viagemId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Iniciar viagem?",
                    text: "Tem certeza que deseja iniciar esta viagem?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, iniciar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/viagens/iniciar/${viagemId}`, {
                                method: "PATCH",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Iniciada!", data.success, "success").then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire("Erro!", data.error, "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Erro!", "Ocorreu um problema ao iniciar a viagem.",
                                    "error");
                            });
                    }
                });
            });
        });
        document.querySelectorAll(".finalizar-btn").forEach(button => {
            button.addEventListener("click", function() {
                let viagemId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Finalizar viagem?",
                    html: `
                    <label for="km_fim" class="swal2-label">KM de Chegada:</label>
                    <input type="number" id="km_fim" class="swal2-input" placeholder="Informe o KM final" required>
                    
                    <label for="data_hora_fim" class="swal2-label">Data e Hora de Chegada:</label>
                    <input type="datetime-local" id="data_hora_fim" class="swal2-input" required>
                `,
                    showCancelButton: true,
                    confirmButtonText: "Finalizar",
                    cancelButtonText: "Cancelar",
                    preConfirm: () => {
                        let kmFim = document.getElementById("km_fim").value;
                        let dataHoraFim = document.getElementById("data_hora_fim").value;

                        if (!kmFim || !dataHoraFim) {
                            Swal.showValidationMessage("Por favor, preencha todos os campos!");
                        }

                        return {
                            kmFim,
                            dataHoraFim
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/viagens/finalizar/${viagemId}`, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    km_fim: result.value.kmFim,
                                    data_hora_fim: result.value.dataHoraFim
                                })
                            })
                            .then(response => response.json()
                                .then(data => ({
                                    status: response.status,
                                    body: data
                                }))
                            )
                            .then(({
                                status,
                                body
                            }) => {
                                if (status === 200) {
                                    Swal.fire("Finalizada!", body.success, "success").then(
                                () => {
                                        location.reload();
                                    });
                                } else if (status === 422) {
                                    let errorMessage = Object.values(body.errors).join('<br>');
                                    Swal.fire("Erro de Validação", errorMessage, "error");
                                } else {
                                    Swal.fire("Erro!", body.error ||
                                        "Ocorreu um problema ao finalizar a viagem.",
                                        "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Erro!", "Ocorreu um problema ao finalizar a viagem.",
                                    "error");
                            });
                    }
                });
            });
        });
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
                        fetch(`/viagens/delete/${viagemId}`, {
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
                                Swal.fire("Erro!", "Ocorreu um problema ao excluir a viagem.",
                                    "error");
                            });
                    }
                });
            });
        });
    </script>

</x-main-layout>
