<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return $usuarios = Usuario::all();
    }   

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|password'
        ]);

        return Usuario::create($validated);
    }

    public function show($id)
    {
        return Usuario::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'password' => 'required|string|min:6|password',
        ]);

        $usuario->update($validated);

        return $usuario;
    }
}
