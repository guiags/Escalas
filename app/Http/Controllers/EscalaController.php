<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Escala;
use App\Models\Soldado;

class EscalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $escalas = Escala::orderBy('data')->get(); // Busca todas, ordenados pela data
        return view('escalas.index', compact('escalas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('escalas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required|date|after_or_equal:today', // A data não pode ser no passado
            'turno' => 'required|string|in:manha,tarde,noite',
            'servico' => 'required|string|max:255',
            'vagas_necessarias' => 'required|integer|min:1',
        ]);
    
        // 2. Criação no Banco de Dados
        Escala::create($validatedData);
    
        // 3. Redirecionamento com Mensagem de Sucesso
        return redirect()->route('escalas.index')->with('success', 'Escala de serviço criada com sucesso! Agora você pode atribuir soldados a ela.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Escala $escala)
    {
        // Garante que a relação soldados seja carregada
        $escala->load('soldados');

        // Puxa todos os soldados disponíveis para o dropdown (select)
        $soldadosDisponiveis = Soldado::orderBy('nome_guerra')->get();

        return view('escalas.show', [
            'escala' => $escala,
            'soldadosDisponiveis' => $soldadosDisponiveis
        ]);
    }

    /**
     * Anexa um soldado à escala (MÉTODO CUSTOMIZADO: ATTACH)
     */
    public function attachSoldier(Request $request, Escala $escala)
    {
        $request->validate([
            'soldado_id' => 'required|exists:soldados,id',
        ]);

        $soldadoId = $request->input('soldado_id');

        // Evita duplicidade e verifica limite de vagas (LÓGICA CRÍTICA)
        if ($escala->soldados->count() >= $escala->vagas_necessarias) {
            return back()->with('error', 'A escala já atingiu o número máximo de vagas.');
        }

        try {
            // Anexa o soldado usando a relação BelongsToMany
            $escala->soldados()->attach($soldadoId);
        } catch (\Exception $e) {
            // Se já estiver anexado, o attach pode falhar dependendo da configuração da migration (unique)
            return back()->with('error', 'Este soldado já está escalado neste serviço.');
        }

        return back()->with('success', 'Soldado atribuído à escala com sucesso!');
    }

    /**
     * Remove um soldado da escala (MÉTODO CUSTOMIZADO: DETACH)
     */
    public function detachSoldier(Request $request, Escala $escala)
    {
        $request->validate([
            'soldado_id' => 'required|exists:soldados,id',
        ]);
        
        // Desanexa o soldado da escala
        $escala->soldados()->detach($request->input('soldado_id'));

        return back()->with('success', 'Soldado removido da escala com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
