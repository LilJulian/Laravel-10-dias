<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimiento = Movimiento::with('producto')->get();

        return $movimiento;
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
        ]);

        $producto = Producto::findOrFail($validated['producto_id']);

        return DB::transaction(function () use ($validated, $producto) {
            if ($validated['tipo'] === 'entrada') {
                $producto->stock += $validated['cantidad'];
            } else {
                if ($producto->stock < $validated['cantidad']) {
                    return response()->json(['error' => 'Stock insuficiente'], 400);
                }
                $producto->stock -= $validated['cantidad'];
            }

            $producto->save();

            $movimiento = Movimiento::create($validated);

            return response()->json($movimiento, 201);
        });
    }

    public function show($id)
    {         
        return Movimiento::with('producto')->findOrFail($id);
    }

    public function destroy($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->delete();

        return response()->json(['message' => 'Movimiento eliminado']);
    }
}
