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
        $viagens = Viagem::with('motoristas')->get();
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
    
        if (!is_array($request->motoristas) || count($request->motoristas) === 0) {
            return response()->json(['error' => 'É necessário selecionar pelo menos um motorista.'], 422);
        }
    
        $validator = \Validator::make($request->all(), [
            'veiculo_id' => 'required|exists:veiculos,id',
            'motoristas' => 'required|array|min:1',
            'motoristas.*.id' => 'required|exists:motoristas,id',
            'motoristas.*.tipo' => 'required|in:Principal,Auxiliar',
            'km_inicio' => 'required|numeric|min:0',
            'data_hora_inicio' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->lt(Carbon::today())) {
                        $fail('A data de início não pode ser anterior à data atual.');
                    }
                },
            ],
            'data_hora_fim' => 'required|date|after:data_hora_inicio',
        ], [
            'motoristas.required' => 'É necessário selecionar pelo menos um motorista.',
            'motoristas.*.id.required' => 'Cada motorista deve ter um ID.',
            'motoristas.*.id.exists' => 'Um dos motoristas selecionados não existe.',
            'motoristas.*.tipo.required' => 'O tipo do motorista é obrigatório.',
            'motoristas.*.tipo.in' => 'O tipo do motorista deve ser "Principal" ou "Auxiliar".',
            'data_hora_fim.after' => 'A data/hora de fim deve ser posterior à data/hora de início.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $veiculo = Veiculo::findOrFail($request->veiculo_id);
        if ($request->km_inicio != $veiculo->km_atual) {
            return response()->json([
                'error' => 'O KM inicial informado não corresponde ao KM atual do veículo. Por favor, verifique e tente novamente.'
            ], 422);
        }
    
        $viagem = Viagem::create([
            'veiculo_id' => $request->veiculo_id,
            'km_inicio' => $request->km_inicio,
            'data_hora_inicio' => Carbon::parse($request->data_hora_inicio),
            'data_hora_fim' => Carbon::parse($request->data_hora_fim),
            'status' => 'AGUARDANDO INICIO',
        ]);
    
        $motoristasArray = [];
        foreach ($request->motoristas as $motorista) {
            $motoristasArray[$motorista['id']] = ['tipo_motorista' => $motorista['tipo']];
        }
    
        $viagem->motoristas()->attach($motoristasArray);
        return response()->json(['success' => 'Viagem cadastrada com sucesso!', 'viagem' => $viagem->load('motoristas')], 201);
        
    }
    
    public function edit($id)
    {
        $viagem = Viagem::findOrFail($id);

        if ($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser editadas.'], 422);
        }

        $motoristasDisponiveis = Motorista::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->orWhereHas('viagens', function ($query) use ($viagem) {
            $query->where('id', $viagem->id);
        })->get();

        $veiculosDisponiveis = Veiculo::whereDoesntHave('viagens', function ($query) {
            $query->where('status', 'EM ANDAMENTO');
        })->orWhereHas('viagens', function ($query) use ($viagem) {
            $query->where('id', $viagem->id);
        })->get();

        return view('viagens.edit', compact('veiculosDisponiveis', 'motoristasDisponiveis', 'viagem'));
    }
    public function iniciar($id)
    {
        $viagem = Viagem::findOrFail($id);
        if ($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser iniciadas.']);
        }
        $viagem->update(['status' => 'EM ANDAMENTO']);
        return response()->json(['success' => 'Viagem iniciada!']);
    }
    public function finalizar(Request $request, $id)
    {
        $viagem = Viagem::findOrFail($id);

        if ($viagem->status !== 'EM ANDAMENTO') {
            return response()->json(['error' => 'Apenas viagens "EM ANDAMENTO" podem ser finalizadas.']);
        }

        $validator = \Validator::make($request->all(), [
            'km_fim' => 'required|numeric|min:' . $viagem->km_inicio,
            'data_hora_fim' => [
                'required',
                'date',
                'after_or_equal:' . $viagem->data_hora_inicio,
                function ($attribute, $value, $fail) {
                    $now = now()->setTimezone('America/Cuiaba');
                    if (\Carbon\Carbon::parse($value)->greaterThan($now)) {
                        $fail('A data/hora de fim não pode estar no futuro.');
                    }
                },
            ],
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
            'motoristas' => 'sometimes|array',
            'motoristas.*' => 'exists:motoristas,id',
            'km_inicio' => 'required|numeric|min:0',
            'data_hora_inicio' => 'required|date',
            'data_hora_fim' => 'required|date|after:data_hora_inicio',
        ], [
            'data_hora_fim.after' => 'A data/hora de fim deve ser posterior a data/hora de inicio.'
        ]);

        $viagem->update($request->only(['veiculo_id', 'km_inicio', 'data_hora_inicio', 'data_hora_fim', 'status']));
        if ($request->has('motoristas')) {
            $viagem->motoristas()->sync($request->motoristas);
        }

        return response()->json(['success' => 'Viagem atualizada com sucesso', 'viagem' => $viagem->load('motoristas')]);
    }
    public function destroy($id)
    {
        $viagem = Viagem::findOrFail($id);

        if ($viagem->status !== 'AGUARDANDO INICIO') {
            return response()->json(['error' => 'Apenas viagens "AGUARDANDO INICIO" podem ser excluídas.'], 422);
        }

        $viagem->motoristas()->detach();
        $viagem->delete();

        return response()->json(['success' => 'Viagem excluída com sucesso']);
    }
}
