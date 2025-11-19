<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categoria = Categoria::all();

        if ($categoria->isEmpty()) {
            return response()->json(['message' => 'No hay categorías disponibles'], 404);
        }
        return $categoria;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:categorias',
        ]);

        // Crear usando solo los datos validados
        return Categoria::create($validated);
    
    }

    public function show($id)
    {
        return Categoria::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|unique:categorias,nombre,' . $id,
        ]);

        $categoria->update($request->all());

        return $categoria;
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada']);
    }
}
