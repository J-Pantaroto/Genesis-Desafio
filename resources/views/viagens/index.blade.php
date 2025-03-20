<x-main-layout>
    <div class="container mt-4">
        <h1 class="text-center">Viagens</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('viagens.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Viagem</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Motoristas</th>
                        <th class="text-center" scope="col">Veículo</th>
                        <th class="text-center" scope="col">Início</th>
                        <th class="text-center" scope="col">Chegada</th>
                        <th class="text-center" scope="col">KM de Saída</th>
                        <th class="text-center" scope="col">KM de Chegada</th>
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($viagens->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">Nenhuma viagem cadastrada!</td>
                        </tr>
                    @else
                        @foreach ($viagens as $viagem)
                            <tr>
                                <th class="text-center" scope="row">{{ $viagem->id }}</th>

                                {{-- Exibição dos Motoristas --}}
                                <td class="text-center">
                                    @if ($viagem->motoristas->isNotEmpty())
                                        @foreach ($viagem->motoristas as $motorista)
                                            {{ $motorista->nome }} <br>
                                        @endforeach
                                    @else
                                        A definir
                                    @endif
                                </td>

                                {{-- Exibição do Veículo --}}
                                <td class="text-center">
                                    {{ $viagem->veiculo->modelo ?? 'A definir' }}
                                </td>

                                <td class="text-center">{{ $viagem->data_hora_inicio }}</td>
                                <td class="text-center">{{ $viagem->data_hora_fim }}</td>
                                <td class="text-center">{{ $viagem->km_inicio }}</td>
                                <td class="text-center">{{ $viagem->km_fim }}</td>
                                <td class="status {{ str_replace(' ', '-', $viagem->status) }}">{{ $viagem->status }}</td>
                                <td class="text-center">
                                    @if ($viagem->status === 'AGUARDANDO INICIO')
                                        <button class="btn btn-success btn-sm iniciar-btn m-1"
                                            data-id="{{ $viagem->id }}"><i class="fas fa-play"></i> Iniciar</button>
                                    @elseif($viagem->status === 'EM ANDAMENTO')
                                        <button class="btn btn-primary btn-sm finalizar-btn m-1"
                                            data-id="{{ $viagem->id }}"><i class="fas fa-flag-checkered"></i>
                                            Finalizar</button>
                                    @endif

                                    <a data-id="{{ $viagem->id }}" href="{{ route('viagens.edit', $viagem->id) }}"
                                        class="btn btn-warning btn-sm m-1 edit-btn"><i class="fas fa-edit"></i>
                                        Editar</a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $viagem->id }}"><i
                                            class="fa-solid fa-trash fa-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/viagens.js') }}"></script>
</x-main-layout>
