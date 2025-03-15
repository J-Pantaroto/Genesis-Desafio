<?php
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/', [ViagemController::class, 'viagensDoDia']);


Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
Route::get('/veiculos/create', [VeiculoController::class, 'create'])->name('veiculos.create');
Route::post('/veiculos', [VeiculoController::class, 'store'])->name('veiculos.store');
Route::get('/veiculos/{id}/edit', [VeiculoController::class, 'edit'])->name('veiculos.edit');
Route::put('/veiculos/{id}', [VeiculoController::class, 'update'])->name('veiculos.update');
Route::delete('/veiculos/{id}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');

Route::get('/motoristas', [MotoristaController::class, 'index'])->name('motoristas.index');
Route::get('/motoristas/create', [MotoristaController::class, 'create'])->name('motoristas.create');
Route::post('/motoristas', [MotoristaController::class, 'store'])->name('motoristas.store');
Route::get('/motoristas/{id}/edit', [MotoristaController::class, 'edit'])->name('motoristas.edit');
Route::put('/motoristas/{id}', [MotoristaController::class, 'update'])->name('motoristas.update');
Route::delete('/motoristas/{id}', [MotoristaController::class, 'destroy'])->name('motoristas.destroy');

Route::get('/viagens', [ViagemController::class, 'index'])->name('viagens.index');
Route::get('/viagens/create', [ViagemController::class, 'create'])->name('viagens.create');
Route::post('/viagens', [ViagemController::class, 'store'])->name('viagens.store');
Route::get('/viagens/{id}/edit', [ViagemController::class, 'edit'])->name('viagens.edit');
Route::patch('/viagens/finalizar/{id}', [ViagemController::class, 'finalizar'])->name('viagens.finalizar');
Route::put('/viagens/{id}', [ViagemController::class, 'update'])->name('viagens.update');


Route::get('/viagens/{id}/finalizar', [ViagemController::class, 'mostrarFinalizacao'])->name('viagens.finalizar.view');
Route::put('/viagens/{id}/finalizar', [ViagemController::class, 'finalizar'])->name('viagens.finalizar');

