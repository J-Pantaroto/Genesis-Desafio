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
    public function store(Request $request){
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'cnh' => 'required|string|unique:motoristas,cnh',
        ],[
            'data_nascimento.before_or_equal' => 'O motorista deve ter pelo menos 18 anos',
            'cnh.unique' => 'Este numero de CNH ja esta cadastrado',
        ]);
        Motorista::create($request->all());
        return response()->json(['success' => 'Motorista cadastrado com sucesso']);
    }
    public function edit($id){
        $motorista = Motorista::findOrFail($id);
        return view('motorista.edit',compact('motorista'));
    }
    public function update(Request $request, $id)
    {
        $motorista = Motorista::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'cnh' => 'required|string|unique:motoristas,cnh,' . $id,
        ]);

        $motorista->update($request->all());

        return response()->json(['success' => 'Motorista atualizado com sucesso!']);
    }
    public function destroy($id){
        $motorista = Motorista::findOrFail($id);
        if($motorista->viagens()->count()>0){
            return response()->json(['error' => 'Nao e possivel excluir um motorista com viagens associadas'], 422);
        }
        $motorista->delete();
        return response()->json(['success' => 'Motorista excluido com sucesso']);
    }
}
