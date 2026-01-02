<?php

namespace App\Http\Controllers;

use App\Models\Escala;
use App\Models\Atividade;
use App\Models\Soldado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EscalaController extends Controller
{
    // Lista o histórico de escalas
    public function index(Request $request)
    {
        $query = Escala::with(['atividade', 'soldados']);

        // Filtro por Intervalo de Datas
        if ($request->filled('data_inicio')) {
            $query->where('data', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('data', '<=', $request->data_fim);
        }

        // Filtro por Múltiplas Atividades
        if ($request->filled('atividade_id')) {
            // Se vier do formulário, será um array. O whereIn aceita arrays.
            $query->whereIn('atividade_id', $request->atividade_id);
        }

        // Ordenação e Paginação
        $escalas = $query->orderBy('data', 'desc')->paginate(80);
        
        // Mantém os filtros na paginação (para não perder o filtro ao mudar de página)
        $escalas->appends($request->all());

        $atividades = Atividade::all();

        return view('escalas.index', compact('escalas', 'atividades'));
    }

    // Formulário para gerar nova escala
    public function create()
    {
        $atividades = Atividade::all();
        return view('escalas.create', compact('atividades'));
    }

    // O ALGORITMO DE GERAÇÃO AUTOMÁTICA
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'atividade_id' => 'required|exists:atividades,id',
            'modo_geracao' => 'required|in:automatico,manual', // Novo campo do formulário
        ]);

        $data = $request->input('data');
        $turno = $request->input('turno');
        $atividade = Atividade::findOrFail($request->input('atividade_id'));
        
        // Verifica duplicidade
        if (Escala::where('data', $data)->where('atividade_id', $atividade->id)->exists()) {
            return back()->withErrors(['erro' => 'Já existe uma escala deste tipo para esta data.']);
        }

        // 1. Cria a Escala (Vazia inicialmente)
        $escala = Escala::create([
            'data' => $data,
            'atividade_id' => $atividade->id,
            'observacao' => $request->input('modo_geracao') == 'manual' ? 'Escala criada manualmente.' : 'Gerada automaticamente.',
            'turno' => $turno,
        ]);

        // SE FOR MODO MANUAL (Xerife, Claviculário, etc)
        // Interrompe aqui e manda para a tela de edição
        if ($request->input('modo_geracao') == 'manual') {
            return redirect()->route('escalas.edit', $escala->id)
                            ->with('success', 'Escala criada! Adicione os militares manualmente abaixo.');
        }

        // SE FOR AUTOMÁTICO
        // Lógica de Seleção:
        // 1. Disponível e Sexo Correto
        // 2. Não estar escalado em outra coisa no mesmo dia
        // 3. ORDENAÇÃO: 
        //      Pri: Menos horas NESTA atividade específica.
        //      Sec: Menos horas TOTAIS (Geral + Inicial) para desempate.
        
        $candidatos = Soldado::where('disponivel', true)
            ->when($atividade->sexo_restrito, function($q) use ($atividade) {
                return $q->where('sexo', $atividade->sexo_restrito);
            })
            ->whereDoesntHave('escalas', function ($q) use ($data) {
                $q->where('data', $data);
            })
            ->get()
            ->sortBy([
                // Critério 1: Horas na atividade específica (Ascendente)
                fn($a, $b) => $a->horasPorAtividade($atividade->id) <=> $b->horasPorAtividade($atividade->id),
                // Critério 2: Total Geral (Ascendente) - Desempate
                fn($a, $b) => $a->total_geral <=> $b->total_geral,
            ]);

        // Seleção (Tentando diversificar turmas)
        $selecionados = collect();
        $turmasSelecionadas = [];
        $qtde = $atividade->quantidade_padrao;

        foreach ($candidatos as $soldado) {
            if ($selecionados->count() >= $qtde) break;

            // Tenta pegar de turma diferente primeiro
            if (!in_array($soldado->turma, $turmasSelecionadas)) {
                $selecionados->push($soldado);
                $turmasSelecionadas[] = $soldado->turma;
            }
        }

        // Se faltou gente, completa com o resto da fila ordenada
        if ($selecionados->count() < $qtde) {
            $restantes = $candidatos->diff($selecionados);
            foreach ($restantes as $soldado) {
                if ($selecionados->count() >= $qtde) break;
                $selecionados->push($soldado);
            }
        }

        if ($selecionados->isEmpty()) {
            $escala->delete(); // Desfaz a criação se falhar
            return back()->withErrors(['erro' => 'Não há militares disponíveis para os critérios.']);
        }

        $escala->soldados()->attach($selecionados->pluck('id'));

        return redirect()->route('escalas.show', $escala->id)
                        ->with('success', 'Escala gerada automaticamente com ' . $selecionados->count() . ' militares.');
    }

    // Visualizar detalhes da escala
    public function show(Escala $escala)
    {
        $escala->load(['soldados', 'atividade']);
        return view('escalas.show', compact('escala'));
    }

    public function edit(Escala $escala)
    {
        // Carrega os soldados já escalados
        $escala->load('soldados');

        // Carrega soldados disponíveis para adicionar (que NÃO estão nesta escala)
        // Opcional: Filtra pelo sexo da atividade para evitar erros
        $query = Soldado::whereDoesntHave('escalas', function($q) use ($escala) {
            $q->where('escala_soldado.escala_id', $escala->id);
        })->where('disponivel', true);

        if ($escala->atividade->sexo_restrito) {
            $query->where('sexo', $escala->atividade->sexo_restrito);
        }

        $disponiveis = $query->orderBy('nome_guerra')->get();

        return view('escalas.edit', compact('escala', 'disponiveis'));
    }

    // Excluir escala (remove as horas do banco dos soldados)
    public function destroy(Escala $escala)
    {
        $escala->delete();
        return redirect()->route('escalas.index')->with('success', 'Escala excluída.');
    }

    // Adiciona um soldado manualmente à escala
    public function adicionarSoldado(Request $request, Escala $escala)
    {
        $request->validate([
            'soldado_id' => 'required|exists:soldados,id',
        ]);

        $soldado = Soldado::findOrFail($request->soldado_id);

        // Validação de segurança (Sexo)
        if ($escala->atividade->sexo_restrito && $soldado->sexo !== $escala->atividade->sexo_restrito) {
            return back()->withErrors(['erro' => 'Este militar não corresponde ao sexo exigido pela atividade.']);
        }

        // Verifica se já está na escala (evitar duplicidade)
        if ($escala->soldados()->where('soldado_id', $soldado->id)->exists()) {
            return back()->withErrors(['erro' => 'Este militar já está na escala.']);
        }

        $escala->soldados()->attach($soldado->id);

        return back()->with('success', 'Militar adicionado manualmente.');
    }

    // Remove um soldado da escala
    public function removerSoldado(Escala $escala, Soldado $soldado)
    {
        $escala->soldados()->detach($soldado->id);
        return back()->with('success', 'Militar removido da escala.');
    }

    public function imprimirEmMassa(Request $request)
    {
        $ids = $request->input('escalas_selecionadas');

        if (empty($ids)) {
            return back()->with('error', 'Nenhuma escala foi selecionada para impressão.');
        }

        // Busca todas as escalas selecionadas com seus soldados
        $escalas = Escala::whereIn('id', $ids)
            ->with(['soldados', 'atividade'])
            ->orderBy('data', 'asc') // Ordena por data
            ->get();

        return view('escalas.print-multiple', compact('escalas'));
    }

    public function createAutomacao()
    {
        $atividades = Atividade::all();
        // Busca as turmas distintas para facilitar a seleção (opcional, pode ser campo texto)
        $turmas = Soldado::select('turma')->distinct()->whereNotNull('turma')->pluck('turma');
        
        return view('escalas.automacao', compact('atividades', 'turmas'));
    }

    public function storeAutomacao(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'turma' => 'nullable|string',
            
            // Validações para as duas escalas
            'atividade_xerife_id' => 'required|exists:atividades,id',
            'bone_xerife' => 'required|integer',
            
            'atividade_sub_id' => 'required|exists:atividades,id',
            'bone_sub' => 'required|integer',
        ]);

        $startDate = Carbon::parse($request->data_inicio);
        $endDate = Carbon::parse($request->data_fim);
        $turma = $request->turma;

        // --- 1. Buscar Soldados da Turma ---
        $query = Soldado::query();
        if ($turma) {
            $query->where('turma', $turma);
        }
        $soldados = $query->orderBy('numero_bone')->get();
        $totalSoldados = $soldados->count();

        if ($soldados->isEmpty()) {
            return back()->withErrors(['msg' => 'Nenhum soldado encontrado.']);
        }

        // --- 2. Encontrar índices iniciais ---
        // Índice do Xerife
        $indexXerife = $soldados->search(function ($s) use ($request) {
            return $s->numero_bone == $request->bone_xerife;
        });
        
        // Índice do Subxerife
        $indexSub = $soldados->search(function ($s) use ($request) {
            return $s->numero_bone == $request->bone_sub;
        });

        if ($indexXerife === false) {
            return back()->withErrors(['msg' => "Soldado Xerife (Boné {$request->bone_xerife}) não encontrado."]);
        }
        if ($indexSub === false) {
            return back()->withErrors(['msg' => "Soldado Subxerife (Boné {$request->bone_sub}) não encontrado."]);
        }

        $currentDate = $startDate->copy();
        $createdCount = 0;
        $offset = 0; // Controla o avanço dos dias

        DB::beginTransaction();
        try {
            while ($currentDate->lte($endDate)) {
                
                // Determinar quem são os soldados do dia atual
                // O operador % (módulo) faz a lista "dar a volta" (loop) quando chega no fim
                $idxAtualXerife = ($indexXerife + $offset) % $totalSoldados;
                $idxAtualSub = ($indexSub + $offset) % $totalSoldados;

                $soldadoXerife = $soldados[$idxAtualXerife];
                $soldadoSub = $soldados[$idxAtualSub];

                // --- 3. Verificar Conflitos ---
                // Verifica se já estão escalados neste dia (ignora se for a mesma escala que estamos criando agora, mas bloqueia duplicidade)
                $conflitos = DB::table('escala_soldado')
                    ->join('escalas', 'escala_soldado.escala_id', '=', 'escalas.id')
                    ->where('escalas.data', $currentDate->format('Y-m-d'))
                    ->whereIn('escala_soldado.soldado_id', [$soldadoXerife->id, $soldadoSub->id])
                    ->count();

                if ($conflitos > 0) {
                    throw new \Exception("Conflito no dia " . $currentDate->format('d/m/Y') . ": Um dos soldados já está escalado.");
                }

                // --- 4. Criar Escala do XERIFE ---
                $escalaXerife = Escala::create([
                    'data' => $currentDate->format('Y-m-d'),
                    'atividade_id' => $request->atividade_xerife_id,
                ]);
                $escalaXerife->soldados()->attach($soldadoXerife->id);
                $createdCount++;

                // --- 5. Criar Escala do SUBXERIFE ---
                $escalaSub = Escala::create([
                    'data' => $currentDate->format('Y-m-d'),
                    'atividade_id' => $request->atividade_sub_id,
                ]);
                $escalaSub->soldados()->attach($soldadoSub->id);
                $createdCount++;

                // Avança para o próximo dia e próximo soldado na fila
                $currentDate->addDay();
                $offset++;
            }
            
            DB::commit();
            return redirect()->route('escalas.index')->with('success', "Automação concluída! $createdCount registros de escala criados.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Erro: ' . $e->getMessage()]);
        }
    }


}