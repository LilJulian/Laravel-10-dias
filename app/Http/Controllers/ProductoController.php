<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $producto = Producto::with('categoria')->get();
        if ($producto->isEmpty()) {
            return response()->json(['message' => 'No hay productos disponibles'], 404);
        }
        return $producto;
        
    }

    public function store(Request $request)
    {
        $validacion = $request->validate([
            'nombre' => 'required|unique:productos',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        return Producto::create($request->all());
    }

    public function show($id)
    {
        $producto = Producto::with('categoria', 'movimientos')->findOrFail($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return $producto;
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validacion = $request->validate([
            'nombre' => 'required|unique:productos,nombre,' . $id,
            'stock' => 'integer|min:0',
            'precio' => 'numeric|min:0',
            'categoria_id' => 'exists:categorias,id',
        ]);
        $producto->update($request->all());

        return $producto;
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['message' => 'Producto eliminado']);
    }
}
