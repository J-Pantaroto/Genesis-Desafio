<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;

class ViagemController extends Controller
{
    public function index()
    {
        $viagens = Viagem::all();
        return view('viagens.index', compact('viagens'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id',
            'motorista_id' => 'required|exists:motoristas,id',
            'km_inicio' => 'required|numeric|min:0',
            'data_hora_inicio' => 'required|date|after_or_equal:now',
            'data_hora_fim' => 'required|date|after:data_hora_inicio|after_or_equal:now',
        ], [
            'data_hora_inicio.after_or_equal' => 'A data/hora de inicio não pode ser anterior a data/hora atual.',
            'data_hora_fim.after' => 'A data/hora de fim deve ser posterior a data/hora de inicio.',
            'data_hora_fim.after_or_equal' => 'A data/hora de fim nao pode ser anterior a data/hora atual.'
        ]);
        Viagem::create($request->all());

        return response()->json(['success' => 'Viagem cadastrada com sucesso']);

    }
    public function edit($id){
        $viagem = Viagem::findOrFail($id);
        if ($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser editadas.']);
        }
        return view('viagem.edit',compact('viagem'));
    }
    public function mostrarFinalizacao($id)
    {
        $viagem = Viagem::findOrFail($id);
    
        if ($viagem->status !== 'EM ANDAMENTO') {
            return response()->json(['error' => 'Apenas viagens "EM ANDAMENTO" podem ser finalizadas']);
        }
    
        return response()->json([
            'veiculo' => $viagem->veiculo->modelo,
            'motorista' => $viagem->motorista->nome,
            'km_inicio' => $viagem->km_inicio,
            'km_fim' => null,
            'data_hora_inicio' => $viagem->data_hora_inicio,
            'data_hora_fim' => $viagem->data_hora_fim,
            'id' => $viagem->id
        ]);
    }
    
    public function finalizar(Request $request, $id){
        $viagem = Viagem::findOrFail($id);
        if ($viagem->status !== 'EM ANDAMENTO') {
            return response()->json(['error' => 'Apenas viagens "EM ANDAMENTO" podem ser finalizadas.']);
        }
        $request->validate([
            'km_fim' => 'required|numeric|min:' . $viagem->km_inicio,
            'data_hora_fim' => 'required|date|after_or_equal:' . $viagem->data_hora_inicio,
        ], [
            'km_fim.min' => 'O KM final deve ser maior ou igual ao KM inicial',
            'data_hora_fim.after_or_equal' => 'A data/hora de fim deve ser posterior ou igual a data de inicio',
        ]);



        $viagem->update([
            'km_fim' => $request->km_fim,
            'data_hora_fim' => $request->data_hora_fim,
            'status' => 'FINALIZADA'
        ]);
        $viagem->veiculo->update(['km_atual' => $request->km_fim]);
        return response()->json(['success' => 'Viagem finalizada']);
    }
    public function update(Request $request, $id)
{
    $viagem = Viagem::findOrFail($id);

    if ($viagem->status !== 'AGUARDANDO INICIO') {
        return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser editadas.']);
    }

    $request->validate([
        'veiculo_id' => 'required|exists:veiculos,id',
        'motorista_id' => 'required|exists:motoristas,id',
        'km_inicio' => 'required|numeric|min:0',
        'data_hora_inicio' => 'required|date',
        'data_hora_fim' => 'required|date|after:data_hora_inicio',
    ], [
        'data_hora_fim.after' => 'A data/hora de fim deve ser posterior a data/hora de inicio.'
    ]);

    $viagem->update($request->all());

    return response()->json(['success' => 'Viagem atualizada com sucesso']);
}

}
