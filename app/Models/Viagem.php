<?php

namespace App\Models;

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

    public function veiculo(){
        return $this->belongsTo(Veiculo::class);
    }
    public function motorista(){
        return $this->belongsTo(Motorista::class);
    }

}
