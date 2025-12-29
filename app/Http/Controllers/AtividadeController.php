<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use Illuminate\Http\Request;

class AtividadeController extends Controller
{
    /**
     * Lista todas as atividades cadastradas.
     */
    public function index()
    {
        $atividades = Atividade::all();
        return view('atividades.index', compact('atividades'));
    }

    /**
     * Mostra o formulário para criar uma nova atividade.
     */
    public function create()
    {
        return view('atividades.create');
    }

    /**
     * Salva a nova atividade no banco.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'sexo_restrito' => 'nullable|in:M,F', // Null aceita ambos
            'quantidade_padrao' => 'required|integer|min:1',
            'carga_horaria' => 'required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);

        Atividade::create($validated);

        return redirect()->route('atividades.index')
                         ->with('success', 'Atividade criada com sucesso!');
    }

    /**
     * Mostra o formulário para editar uma atividade existente.
     */
    public function edit(Atividade $atividade)
    {
        return view('atividades.edit', compact('atividade'));
    }

    /**
     * Atualiza os dados no banco.
     */
    public function update(Request $request, Atividade $atividade)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'sexo_restrito' => 'nullable|in:M,F',
            'quantidade_padrao' => 'required|integer|min:1',
            'carga_horaria' => 'required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);

        $atividade->update($validated);

        return redirect()->route('atividades.index')
                         ->with('success', 'Atividade atualizada com sucesso!');
    }

    /**
     * Remove a atividade do banco.
     */
    public function destroy(Atividade $atividade)
    {
        // Verifica se existem escalas vinculadas antes de deletar (opcional, mas recomendado)
        if ($atividade->escalas()->exists()) {
             return redirect()->route('atividades.index')
                             ->with('error', 'Não é possível excluir: existem escalas vinculadas a esta atividade.');
        }

        $atividade->delete();

        return redirect()->route('atividades.index')
                         ->with('success', 'Atividade excluída com sucesso!');
    }
}