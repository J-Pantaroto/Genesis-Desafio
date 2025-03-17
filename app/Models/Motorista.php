<?php

namespace App\Models;
use Carbon\Carbon;
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

    public function getDataNascimentoAttribute($value){
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getDataNascimentoIsoAttribute()
{
    return Carbon::createFromFormat('d/m/Y', $this->data_nascimento)->format('Y-m-d');
}

    public function viagens(){
        return $this->hasMany(Viagem::class);
    }
}
