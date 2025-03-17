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
                                <h5 class="card-title">{{ $viagem->motorista->nome }} 🚕</h5>
                                <p class="card-text">
                                    <strong>Veículo:</strong> {{ $viagem->veiculo->modelo }} -
                                    {{ $viagem->veiculo->placa }} <br>
                                    <strong>Início:</strong>
                                    {{ \Carbon\Carbon::parse($viagem->data_hora_inicio)->format('d/m/Y H:i') }} <br>
                                    <strong>Fim:</strong>
                                    {{ \Carbon\Carbon::parse($viagem->data_hora_fim)->format('d/m/Y H:i') }} <br>
                                    <strong>Status:</strong>
                                    @if ($viagem->status == 'AGUARDANDO INÍCIO')
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
