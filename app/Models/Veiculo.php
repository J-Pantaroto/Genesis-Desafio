<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;
    protected $table = 'veiculos';
    protected $fillable = [
        'modelo',
        'ano',
        'data_aquisicao',
        'km_aquisicao',
        'km_atual',
        'renavam',
        'placa',
    ];
    //acessors para data
    public function getDataAquisicaoAttribute($value){
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
    public function viagens(){
        return $this->hasMany(Viagem::class);
    }
}
