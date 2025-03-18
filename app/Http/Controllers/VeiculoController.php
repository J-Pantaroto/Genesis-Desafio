<?php

namespace App\Http\Controllers;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index(){
        $veiculos = Veiculo::all();
        return view('veiculos.index', compact('veiculos'));
    }
    public function create(){
        return view('veiculos.create');
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'modelo' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . date('Y'),
            'data_aquisicao' => 'required|date|before_or_equal:today',
            'km_aquisicao' => 'required|numeric|min:0',
            'renavam' => 'required|string|min:11|max:11|unique:veiculos,renavam',
            'placa' => 'required|string|min:7|max:7|unique:veiculos,placa',
        ], [
            'modelo.required' => 'O campo modelo é obrigatório.',
            'modelo.string' => 'O modelo deve ser um texto válido.',
            'modelo.max' => 'O modelo não pode ter mais de 255 caracteres.',
        
            'ano.required' => 'O campo ano é obrigatório.',
            'ano.integer' => 'O ano do veículo deve ser um número inteiro.',
            'ano.min' => 'O ano do veículo deve ser no mínimo 1900.',
            'ano.max' => 'O ano do veículo não pode estar no futuro.',
        
            'data_aquisicao.required' => 'A data de aquisição é obrigatória.',
            'data_aquisicao.date' => 'A data de aquisição deve ser uma data válida.',
            'data_aquisicao.before_or_equal' => 'A data de aquisição não pode estar no futuro.',
        
            'km_aquisicao.required' => 'A quilometragem de aquisição é obrigatória.',
            'km_aquisicao.numeric' => 'A quilometragem de aquisição deve ser um número válido.',
            'km_aquisicao.min' => 'A quilometragem de aquisição não pode ser negativa.',
        
            'renavam.required' => 'O campo Renavam é obrigatório.',
            'renavam.string' => 'O Renavam deve ser um texto válido.',
            'renavam.unique' => 'Este número de Renavam já está cadastrado.',
            'renavam.max' => 'O renavam deve ter 11 caracteres.',
            'renavam.min' => 'O renavam deve ter 11 caracteres.',
        
            'placa.required' => 'O campo placa é obrigatório.',
            'placa.string' => 'A placa deve ser um texto válido.',
            'placa.max' => 'A placa deve ter 7 caracteres.',
            'placa.min' => 'A placa deve ter 7 caracteres.',
            'placa.unique' => 'Esta placa já está cadastrada em outro veículo.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }           
        $veiculo = Veiculo::create([
            'modelo' => $request->modelo,
            'ano' => $request->ano,
            'data_aquisicao' => $request->data_aquisicao,
            'km_aquisicao' => $request->km_aquisicao,
            'km_atual' => $request->km_aquisicao,
            'renavam' => $request->renavam,
            'placa' => $request->placa,
        ]);
    
        return response()->json(['success' => 'Veiculo cadastrado com sucesso']);
    }

    public function show($id){
        return response()->json(Veiculo::findOrFail($id));
    }
    public function edit($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, $id)
    {
        $veiculo = Veiculo::findOrFail($id);    
        $validator = \Validator::make($request->all(), [
            'modelo' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . date('Y'),
            'data_aquisicao' => 'required|date',
        ], [
            'ano.integer' => 'O campo ano deve ser um número.',
            'ano.min' => 'O ano deve ser maior que 1900.',
            'ano.max' => 'O ano não pode ser maior que o ano atual.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $veiculo->update($request->only(['modelo', 'ano', 'data_aquisicao']));
    
        return response()->json(['success' => 'Veículo atualizado com sucesso!']);
    }
    
    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);
    
        if ($veiculo->viagens()->where('status', 'EM ANDAMENTO')->exists()) {
            return response()->json(['error' => 'Não é possível excluir um veículo com viagens em andamento.'], 422);
        }
    
        $viagensAguardandoIds = [];
        $viagensAguardando = $veiculo->viagens()->where('status', 'AGUARDANDO INICIO')->get();
    
        foreach ($viagensAguardando as $viagem) {
            $viagem->veiculo_id = null; 
            $viagem->save();
            $viagensAguardandoIds[] = $viagem->id;
        }
    
        if ($veiculo->viagens()->where('status', 'FINALIZADA')->exists()) {
            return response()->json([
                'error' => 'Não é possível excluir este veículo, pois ele está associado a viagens finalizadas. O histórico deve ser mantido.'
            ], 422);
        }
    
        $veiculo->delete();
    
        $mensagem = 'Veículo excluído com sucesso.';
        if (!empty($viagensAguardandoIds)) {
            $mensagem .= ' As seguintes viagens estavam aguardando início e agora não possuem veículo: ' 
                       . implode(', ', $viagensAguardandoIds) 
                       . '. Defina um novo veículo ou exclua essas viagens.';
        }
    
        return response()->json([
            'success' => $mensagem
        ]);
    }

}
