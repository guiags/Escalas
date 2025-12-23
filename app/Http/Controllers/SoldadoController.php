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

        $soldados = Soldado::all()->map(function($soldado) {
            $soldado->horas_totais = $soldado->total_horas; 
            return $soldado;
        });
        
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
        // 1. Validação dos dados
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_guerra'   => 'required|string|max:255',
            'matricula'     => 'required|string|unique:soldados,matricula',
            'graduacao'     => 'required|string',
            'turma'         => 'required|string', // Ex: CFsd 2024
            'sexo'          => 'required|in:M,F',
            'numero_bone'   => 'nullable|string',
        ]);

        // 2. Criação do registro no banco
        Soldado::create($validated);

        // 3. Redirecionamento com mensagem de sucesso
        return redirect()->route('soldados.index')
                         ->with('success', 'Soldado cadastrado com sucesso!');
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

public function update(Request $request, Soldado $soldado)
{
    $validated = $request->validate([
        'nome_completo' => 'required|string|max:255',
        'nome_guerra'   => 'required|string|max:255',
        // Ignora a matrícula do próprio soldado na verificação de único
        'matricula'     => 'required|string|unique:soldados,matricula,' . $soldado->id,
        'graduacao'     => 'required|string',
        'turma'         => 'required|string',
        'sexo'          => 'required|in:M,F',
        'numero_bone'   => 'nullable|string',
        'disponivel'    => 'boolean',
    ]);

    // O checkbox não envia nada se desmarcado, então forçamos o valor booleano
    $validated['disponivel'] = $request->has('disponivel');

    $soldado->update($validated);

    return redirect()->route('soldados.index')
                     ->with('success', 'Dados do militar atualizados com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(soldado $soldado)
    {
        $soldado->delete();
        return redirect()->route('soldados.index')->with('success', 'Militar removido.');
    }
}
