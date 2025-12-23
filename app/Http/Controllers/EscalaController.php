<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Escala;
use App\Models\Soldado;
use App\Models\Atividade;
use Illuminate\Support\Facades\DB;

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

    public function gerarEscalaAutomatica(Request $request)
    {
    // 1. Receber dados da escala a ser criada
    $data = $request->input('data');
    $atividadeId = $request->input('atividade_id');
    
    $atividade = Atividade::findOrFail($atividadeId);
    
    // Regras da Atividade
    $qtdeNecessaria = $atividade->quantidade_padrao;
    $sexoRestrito = $atividade->sexo_restrito; // 'M', 'F' ou null
    
    // 2. Query Base de Soldados Disponíveis
    $query = Soldado::query()->where('disponivel', true);

    // Filtro de Sexo (se a atividade exigir)
    if ($sexoRestrito) {
        $query->where('sexo', $sexoRestrito);
    }

    // Filtro: Não pode estar escalado em outra atividade no MESMO dia
    $query->whereDoesntHave('escalas', function($q) use ($data) {
        $q->where('data', $data);
    });

    // 3. Algoritmo de Prioridade (Menos Escalados Primeiro)
    // Precisamos calcular as horas totais via query para ordenar
    // Isso soma a carga_horaria de todas as atividades que o soldado já fez
    $candidatos = $query->withSum(['escalas as horas_totais' => function($q) {
        $q->join('atividades', 'escalas.atividade_id', '=', 'atividades.id')
          ->select(DB::raw('COALESCE(SUM(atividades.carga_horaria), 0)'));
    }], 'horas_totais')
    ->orderBy('horas_totais', 'asc') // Os menos cansados primeiro
    ->get();

    // 4. Seleção com Diversidade de Turma
    $selecionados = collect();
    $turmasSelecionadas = [];

    foreach ($candidatos as $soldado) {
        if ($selecionados->count() >= $qtdeNecessaria) {
            break;
        }

        // Tenta priorizar turmas diferentes, mas se não tiver opção, repete
        // Lógica: Se a turma ainda não foi selecionada OU se já rodamos todos e ainda falta gente
        if (!in_array($soldado->turma, $turmasSelecionadas)) {
            $selecionados->push($soldado);
            $turmasSelecionadas[] = $soldado->turma;
        } else {
            // Se chegamos aqui, é porque todos os candidatos "ideais" (turma nova) acabaram
            // Podemos colocar numa lista de espera ou aceitar repetir turma se necessário
            // Para simplificar, vou permitir repetir se não houver opção melhor depois:
             continue; 
        }
    }
    
    // Se não preencheu com turmas distintas, preenche com o restante da lista ordenada
    if ($selecionados->count() < $qtdeNecessaria) {
        $faltantes = $qtdeNecessaria - $selecionados->count();
        $idsJaSelecionados = $selecionados->pluck('id')->toArray();
        
        $extras = $candidatos->whereNotIn('id', $idsJaSelecionados)->take($faltantes);
        $selecionados = $selecionados->merge($extras);
    }

    // 5. Salvar a Escala
    $escala = Escala::create([
        'data' => $data,
        'atividade_id' => $atividade->id,
    ]);

    // Anexar os IDs dos soldados selecionados
    $escala->soldados()->attach($selecionados->pluck('id'));

    return redirect()->back()->with('success', 'Escala gerada com ' . $selecionados->count() . ' militares.');
}

}
