<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    use SoftDeletes;
    protected $table = 'motoristas';
    protected $fillable = [
        'nome',
        'data_nascimento',
        'cnh'
    ];
    public function viagens(){
        return $this->hasMany(Viagem::class);
    }
}
