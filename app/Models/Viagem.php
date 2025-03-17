<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viagem extends Model
{
    use HasFactory;
    protected $table = 'viagens';
    protected $fillable = [
        'motorista_id',
        'veiculo_id',
        'km_inicio',
        'km_fim',
        'data_hora_inicio',
        'data_hora_fim',
        'status'
    ];

    //acessors para datas

    public function getDataHoraInicioAttribute($value){
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
    public function getDataHoraFimAttribute($value){
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
    public function veiculo(){
        return $this->belongsTo(Veiculo::class);
    }
    public function motorista(){
        return $this->belongsTo(Motorista::class);
    }

}
