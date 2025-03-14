<?php

namespace App\Observers;

use App\Models\Viagem;
use Illuminate\Support\Carbon;

class ViagemObserver
{
    // um pouco antes d criar a viagem vai sugerir como km de inicio o km_atual
    public function creating(Viagem $viagem){
        if (!$viagem->km_inicio) {
            $viagem->km_inicio = $viagem->veiculo->km_atual;
        }
    }
    public function created(Viagem $viagem){
        $viagem->veiculo->update(['km_atual' => $viagem->km_inicio]);
    }

    //caso a hora ou data atual seja diferente da hora prevista ao cadastrar a viagem ira alterar a variavel data_hora_fim para a data e hora atual
    public function updated(Viagem $viagem){
        if ($viagem->status === 'FINALIZADO') {
            if ($viagem->data_hora_fim != Carbon::now()) {
                $viagem->data_hora_fim = Carbon::now();
            }
            //atualiza o km_atual com o km_fim
            if($viagem->km_fim && $viagem->km_fim >= $viagem->km_inicio){
                $viagem->veiculo->update(['km_atual' => $viagem->km_fim]);
            }
        }
    }
}
