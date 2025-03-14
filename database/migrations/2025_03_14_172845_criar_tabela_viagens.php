<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('viagens', function (Blueprint $table){
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('restrict');
            $table->foreignId('motorista_id')->constrained('motoristas')->onDelete('restrict');
            $table->bigInteger('km_inicio');
            $table->bigInteger('km_fim');
            $table->dateTime('data_hora_inicio');
            $table->dateTime('data_hora_fim');
            $table->enum('status',['AGUARDANDO INICIO', 'EM ANDAMENTO', 'FINALIZADO'])->default('AGUARDANDO INICIO');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('viagens');
    }
};
