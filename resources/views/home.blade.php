<x-main-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-center">Viagens do Dia 🚗</h2>
        </div>

        @if ($viagens->isEmpty())
            <div class="alert alert-info text-center mt-3">Nenhuma viagem cadastrada para hoje.</div>
        @else
            <div class="row mt-3">
                @foreach ($viagens as $viagem)
                    <div class="col-md-6">
                        <div class="card viagem-card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">🚕 Motoristas:</h5>
                                <ul class="list-unstyled">
                                    @foreach ($viagem->motoristas as $motorista)
                                        <li>{{ $motorista->nome }} - <strong>{{ $motorista->pivot->tipo_motorista }}</strong></li>
                                    @endforeach
                                </ul>
                                
                                <p class="card-text">
                                    <strong>Veículo:</strong> 
                                    {{ $viagem->veiculo->modelo ?? 'N/A' }} - 
                                    {{ $viagem->veiculo->placa ?? 'N/A' }} <br>

                                    <strong>Início:</strong> 
                                    {{ $viagem->data_hora_inicio ?? 'N/A' }} <br>

                                    <strong>Fim:</strong> 
                                    {{ $viagem->data_hora_fim ?? 'N/A' }} <br>

                                    <strong>Status:</strong>
                                    @if ($viagem->status == 'AGUARDANDO INICIO')
                                        <span class="status status-aguardando">🟡 Aguardando</span>
                                    @elseif($viagem->status == 'EM ANDAMENTO')
                                        <span class="status status-andamento">🟢 Em Andamento</span>
                                    @else
                                        <span class="status status-finalizado">🔴 Finalizada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-main-layout>
