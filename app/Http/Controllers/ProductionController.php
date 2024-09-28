<?php
namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductionController extends Controller
{
    public function index(Request $request)
    {


        // Obtém as produções com paginação
        $productions = Production::paginate($request->perPage ?? 5);

        return Inertia::render('Configuracoes', [
            'productions' => $productions,
        ]);
    }

    public function create()
    {
        return Inertia::render('Productions/Create');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Production::create($validatedData);

        return redirect()->route('productions.index')->with('success', 'Produção criada com sucesso!');
    }

    public function edit($id)
    {
        $production = Production::findOrFail($id);
        return Inertia::render('Productions/Edit', [
            'production' => $production,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $production = Production::findOrFail($id);
        $production->update($request->all());

        return redirect()->route('productions.index')->with('success', 'Produção atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete();

        return redirect()->route('productions.index')->with('success', 'Produção deletada com sucesso!');
    }

    public function toggle($id)
    {
        $production = Production::findOrFail($id);
        $production->active = !$production->active;
        $production->save();

        return response()->json(['success' => true, 'active' => $production->active]);
    }
}
