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
        'motorista_id_2',
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
    public function getDataHoraInicioIsoAttribute()
{
    return Carbon::createFromFormat('d/m/Y H:i', $this->data_hora_inicio)->format('Y-m-d\TH:i');
}

public function getDataHoraFimIsoAttribute()
{
    return Carbon::createFromFormat('d/m/Y H:i', $this->data_hora_fim)->format('Y-m-d\TH:i');
}
    public function veiculo(){
        return $this->belongsTo(Veiculo::class);
    }
    public function motorista(){
        return $this->belongsTo(Motorista::class);
    }
    public function motorista2()
    {
        return $this->belongsTo(Motorista::class, 'motorista_id_2');
    }
    
}
