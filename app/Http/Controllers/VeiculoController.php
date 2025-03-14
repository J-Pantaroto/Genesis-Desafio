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

    public function store(Request $request){
        $request->validate([
            'modelo' => 'required|string|max:255',
            'ano' => 'required|date|before_or_equal:today',
            'data_aquisicao' => 'required|date|before_or_equal:today',
            'km_aquisicao' => 'required|numeric|min:0',
            'renavam' => 'required|string|unique:veiculos,renavam',
            'placa' => 'required|string|max:7|unique:veiculos,placa',
        ],[
            'ano.before_or_equal' => 'O ano do veiculo nao pode estar no futuro',
            'data_aquisicao.before_or_equal' => 'A data de aquisicao nao pode estar no futuro'
        ]);
        $veiculo = Veiculo::create([
            'modelo' => $request->modelo,
            'ano' => $request->ano,
            'data_aquisicao' => $request->data_aquisicao,
            'km_aquisicao' => $request->km_aquisicao,
            'km_atual' => $request->km_aquisicao,
            'renavam' => $request->renavam,
            'placa' => $request->placa,
        ]);
        return response()->json($veiculo, 201);
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

        $request->validate([
            'modelo' => 'required|string|max:255',
            'ano' => 'required|date',
            'data_aquisicao' => 'required|date',
            'km_aquisicao' => 'required|numeric|min:0',
            'renavam' => 'required|string|unique:veiculos,renavam,' . $id,
            'placa' => 'required|string|unique:veiculos,placa,' . $id,
        ]);

        $veiculo->update($request->all());

        return response()->json(['success' => 'Veiculo atualizado com sucesso']);
    }
    public function destroy($id){
        $veiculo = Veiculo::findOrFail($id);
        if($veiculo->viagens()->count()>0){
            return response()->json(['error' => 'Nao e possivel excluir um veiculo com viagens'],422);
        }
        $veiculo->delete();
        return response()->json(['success' => 'Veiculo excluido com sucesso']);
    }
}
