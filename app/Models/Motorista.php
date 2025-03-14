<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    use HasFactory;
    protected $table = 'motoristas';
    protected $filable = [
        'nome',
        'data_nascimento',
        'cnh'
    ];
    public function viagens(){
        return $this->hasMany(Viagem::class);
    }
}
