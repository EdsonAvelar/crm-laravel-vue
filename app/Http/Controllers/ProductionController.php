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
        //$productions = Production::orderBy('end_date', 'desc')->paginate($request->perPage ?? 5);

        $productions = Production::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
            ->orderBy('end_date', 'desc')  // Adiciona a ordenação por end_date de forma decrescente
            ->paginate(5);


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

        Production::where('active', true)->update(['active' => false]);
        // Toda nova produção torna-se ativa ao criar
        $validatedData['active'] = true;

        Production::create($validatedData);

        return redirect()->route('productions.index')->with('success', 'Produção criada com sucesso!');
    }

    public function edit($id)
    {
        $production = Production::findOrFail($id);

        Production::where('active', true)->update(['active' => false]);


        return Inertia::render('Productions/Edit', [
            'production' => $production,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'required|boolean',
        ]);

        $production = Production::findOrFail($id);

        // Se a produção estiver marcada como ativa, desativa as outras produções
        if ($validated['active'] & $validated['active']) {


            Production::where('active', true)->where('id', '!=', $production->id)->update(['active' => false]);


        }

        $production->active = $validated['active'];

        $production->update($validated);

        $production->save();

        return redirect()->route('productions.index')->with('success', 'Produção atualizada com sucesso!');
    }

    // public function destroy($id)
    // {
    //     $production = Production::findOrFail($id);
    //     $production->delete();

    //     return redirect()->route('productions.index')->with('success', 'Produção deletada com sucesso!');
    // }

    public function destroy($id)
    {
        // Encontra a produção pelo ID e deleta
        $production = Production::findOrFail($id);
        $production->delete();

        // Após a deleção, busca a produção mais recente pela data de término (end_date)
        $latestProduction = Production::orderBy('end_date', 'desc')->first();

        // Se existir uma produção mais recente, marca-a como ativa
        if ($latestProduction) {
            $latestProduction->active = true;
            $latestProduction->save();

        }

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
