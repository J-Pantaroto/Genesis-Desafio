<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motorista;
class MotoristaController extends Controller
{
    public function index(){
        $motoristas = Motorista::all();
        return view('motoristas.index', compact('motoristas'));
    }

    public function create(){
        return view('motoristas.create');
    }
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'cnh' => 'required|string|regex:/^\d{11}$/|unique:motoristas,cnh'
        ], [
            'data_nascimento.before_or_equal' => 'O motorista deve ter pelo menos 18 anos.',
            'cnh.unique' => 'Este número de CNH já está cadastrado.',
            'cnh.max' => 'A CNH deve conter exatamente 11 dígitos numéricos.',
            'cnh.regex' => 'A CNH deve conter exatamente 11 dígitos numéricos.',
            'cnh.min' => 'A CNH deve conter exatamente 11 dígitos numéricos.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        Motorista::create($request->only(['nome', 'data_nascimento', 'cnh']));
    
        return response()->json(['success' => 'Motorista cadastrado com sucesso']);
    }
    
    public function edit($id){
        $motorista = Motorista::findOrFail($id);
        return view('motoristas.edit',compact('motorista'));
    }
    public function update(Request $request, $id)
    {
        try {
            
            $motorista = Motorista::findOrFail($id);
    
            $validator = \Validator::make($request->all(), [
                'nome' => 'required|string|max:255',
                'data_nascimento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'cnh' => 'required|string|regex:/^\d{11}$/|unique:motoristas,cnh,' . $id,
            ], [
                'data_nascimento.before_or_equal' => 'O motorista deve ter pelo menos 18 anos.',
                'cnh.regex' => 'A CNH deve conter exatamente 11 dígitos numéricos.',
                'cnh.unique' => 'Esta CNH já está cadastrada para outro motorista.',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $motorista->update($request->only(['nome', 'data_nascimento', 'cnh']));
    
            return response()->json(['success' => 'Motorista atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao atualizar o motorista.'], 500);
        }
    }
    
    public function destroy($id)
    {
        $motorista = Motorista::findOrFail($id);
        if ($motorista->viagens()->where('status', 'EM ANDAMENTO')->exists()) {
            return response()->json(['error' => 'Não é possível excluir um motorista com viagens em andamento.'], 422);
        }
    
        $viagensAguardando = $motorista->viagens()->where('status', 'AGUARDANDO INICIO')->get();
        $viagensAguardandoIds = [];
    
        foreach ($viagensAguardando as $viagem) {
            if ($viagem->motorista_id == $id) {
                if (!is_null($viagem->motorista_id_2)) {
                    $viagem->motorista_id = $viagem->motorista_id_2;
                    $viagem->motorista_id_2 = null;
                } else {
                    $viagem->motorista_id = null;
                }
            }
    
            if ($viagem->motorista_id_2 == $id) {
                $viagem->motorista_id_2 = null;
            }
    
            $viagem->save();
            $viagensAguardandoIds[] = $viagem->id;
        }

        $motorista->delete();

        $mensagem = 'Motorista excluído com sucesso.';
        if (!empty($viagensAguardandoIds)) {
            $mensagem .= ' As seguintes viagens estavam aguardando início e tiveram o motorista atualizado ou removido: ' 
                       . implode(', ', $viagensAguardandoIds) 
                       . '. Verifique e defina um novo motorista, se necessário.';
        }
    
        return response()->json(['success' => $mensagem]);
    }
    
}

