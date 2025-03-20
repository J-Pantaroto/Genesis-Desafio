<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viagem_motoristas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viagem_id')->constrained('viagens')->onDelete('cascade');
            $table->foreignId('motorista_id')->constrained('motoristas')->onDelete('cascade');
            $table->string('tipo_motorista')->default('Auxiliar');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('viagem_motoristas');

    }
    
};