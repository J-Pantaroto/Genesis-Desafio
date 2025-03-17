<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Viagem;
use App\Models\Motorista;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class ViagemController extends Controller
{
    public function index()
    {
        $viagens = Viagem::all();
        return view('viagens.index', compact('viagens'));
    }

    public function viagensDoDia()
    {
        $viagens = Viagem::whereDate('data_hora_inicio', Carbon::today())->get();
        return view('home', compact('viagens'));
    }
    public function create()
    {
        $motoristasDisponiveis = Motorista::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->get();
        $veiculosDisponiveis = Veiculo::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->get();
        return view('viagens.create', compact('motoristasDisponiveis', 'veiculosDisponiveis'));
    }
    public function store(Request $request)
    {   
        $validator = \Validator::make($request->all(), [
            'veiculo_id' => 'required|exists:veiculos,id',
            'motorista_id' => 'required|exists:motoristas,id',
            'km_inicio' => 'required|numeric|min:0',
            'data_hora_inicio' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
         
                    if (Carbon::parse($value)->toDateString() < Carbon::today()->toDateString()) {
                        $fail('A data de início não pode ser anterior à data atual.');
                    }
                },
            ],
            'data_hora_fim' => 'required|date|after:data_hora_inicio',
        ], [
            'data_hora_fim.after' => 'A data/hora de fim deve ser posterior à data/hora de início.'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $veiculo = Veiculo::findOrFail($request->veiculo_id);

        if ($request->km_inicio != $veiculo->km_atual) {
            return response()->json([
                'error' => 'O KM inicial informado não corresponde ao KM atual do veículo. Por favor não tente fazer cadastros indevidos!.'
            ], 422);
        }
        Viagem::create($request->all());

        return response()->json(['success' => 'Viagem cadastrada com sucesso']);

    }
    public function edit($id)
    {
        $viagem = Viagem::findOrFail($id);
        $motoristasDisponiveis = Motorista::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->get();
        $veiculosDisponiveis = Veiculo::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->get();
        if ($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser editadas.'],422);
        }
        return view('viagens.edit', compact('veiculosDisponiveis', 'motoristasDisponiveis', 'viagem'));
    }
    public function iniciar($id){
        $viagem = Viagem::findOrFail($id);
        if($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser iniciadas.']);
        }
        $viagem->update(['status' => 'EM ANDAMENTO']);
        return response()->json(['success' => 'Viagem iniciada!']);
    }
    public function finalizar(Request $request, $id){
        $viagem = Viagem::findOrFail($id);
        if ($viagem->status !== 'EM ANDAMENTO') {
            return response()->json(['error' => 'Apenas viagens "EM ANDAMENTO" podem ser finalizadas.']);
        }
        $validator = \Validator::make($request->all(), [
            'km_fim' => 'required|numeric|min:' . $viagem->km_inicio,
            'data_hora_fim' => 'required|date|after_or_equal:' . $viagem->data_hora_inicio,
        ], [
            'km_fim.min' => 'O KM final deve ser maior ou igual ao KM inicial',
            'data_hora_fim.after_or_equal' => 'A data/hora de fim deve ser posterior ou igual à data de início',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $viagem->update([
            'km_fim' => $request->km_fim,
            'data_hora_fim' => $request->data_hora_fim,
            'status' => 'FINALIZADA'
        ]);
        $viagem->veiculo->update(['km_atual' => $request->km_fim]);
        return response()->json(['success' => 'Viagem finalizada!']);
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
    public function destroy($id)
    {
        $viagem = Viagem::findOrFail($id);
        if ($viagem->where('status', 'EM ANDAMENTO')->exists()) {
            return response()->json(['error' => 'Não é possível excluir uma viagem em andamento'], 422);
        }
        if ($viagem->where('status', 'FINALIZADA')->exists()) {
            return response()->json(['error' => 'Não é possível excluir uma viagem finalizada'], 422);
        }
        $viagem->delete();
        return response()->json(['success' => 'Viagem excluida com sucesso']);
    }


}
