<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soldado;

class SoldadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soldados = Soldado::orderBy('nome_guerra')->get(); // Busca todos, ordenados pelo Nome de Guerra
        return view('soldados.index', compact('soldados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('soldados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validação dos Dados
        $validatedData = $request->validate([
            'numero_policia' => 'required|string|unique:soldados,numero_policia|max:255',
            'numero_curso'   => 'nullable|string|max:255',
            'nome_guerra'    => 'required|string|max:255',
            'nome_completo'  => 'required|string|max:255',
        ]);

        // 2. Criação no Banco de Dados
        Soldado::create($validatedData);

        // 3. Redirecionamento com Mensagem de Sucesso
        return redirect()->route('soldados.index')->with('success', 'Soldado cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Soldado $soldado)
    {
        return view('soldados.edit', compact('soldado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Soldado $soldado)
    {
        // 1. Validação dos Dados
        $validatedData = $request->validate([
        // O campo 'numero_policia' deve ser único, exceto para o soldado atual.
        'numero_policia' => 'required|string|max:255|unique:soldados,numero_policia,' . $soldado->id,
        'numero_curso'   => 'nullable|string|max:255',
        'nome_guerra'    => 'required|string|max:255',
        'nome_completo'  => 'required|string|max:255',
    ]);

        // 2. Atualização no Banco de Dados
        $soldado->update($validatedData);

        // 3. Redirecionamento com Mensagem de Sucesso
        return redirect()->route('soldados.index')->with('success', 'Soldado ' . $soldado->nome_guerra . ' atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
