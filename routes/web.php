<?php
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ViagemController::class, 'viagensDoDia'])->name('home');


Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
Route::get('/veiculos/create', [VeiculoController::class, 'create'])->name('veiculos.create');
Route::post('/veiculos/store', [VeiculoController::class, 'store'])->name('veiculos.store');
Route::get('/veiculos/edit/{id}', [VeiculoController::class, 'edit'])->name('veiculos.edit');
Route::put('/veiculos/{id}', [VeiculoController::class, 'update'])->name('veiculos.update');
Route::delete('/veiculos/delete/{id}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');

Route::get('/motoristas', [MotoristaController::class, 'index'])->name('motoristas.index');
Route::get('/motoristas/create', [MotoristaController::class, 'create'])->name('motoristas.create');
Route::post('/motoristas/store', [MotoristaController::class, 'store'])->name('motoristas.store');
Route::get('/motoristas/edit/{id}', [MotoristaController::class, 'edit'])->name('motoristas.edit');
Route::put('/motoristas/{id}', [MotoristaController::class, 'update'])->name('motoristas.update');
Route::delete('/motoristas/delete/{id}', [MotoristaController::class, 'destroy'])->name('motoristas.destroy');

Route::get('/viagens', [ViagemController::class, 'index'])->name('viagens.index');
Route::get('/viagens/create', [ViagemController::class, 'create'])->name('viagens.create');
Route::post('/viagens/store', [ViagemController::class, 'store'])->name('viagens.store');
Route::get('/viagens/edit/{id}', [ViagemController::class, 'edit'])->name('viagens.edit');
Route::post('/viagens/finalizar/{id}', [ViagemController::class, 'finalizar'])->name('viagens.finalizar');
Route::patch('/viagens/iniciar/{id}', [ViagemController::class, 'iniciar'])->name('viagens.iniciar');
Route::put('/viagens/{id}', [ViagemController::class, 'update'])->name('viagens.update');
Route::delete('/viagens/delete/{id}', [ViagemController::class, 'destroy'])->name('viagens.destroy');

