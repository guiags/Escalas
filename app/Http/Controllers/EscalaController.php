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
        // --- LÓGICA DE FILTRO (Mantém o que já fizemos) ---
        $query = Escala::query();

        if ($request->filled('data')) {
            // Ajuste 'data_inicio' se o nome da sua coluna no banco for apenas 'data'
            $query->whereDate('data', $request->input('data'));
        }

        if ($request->filled('atividade')) {
            // Usa 'where' exato pois vem de um dropdown/select
            $query->where('atividade_id', $request->input('atividade'));
        }
        
        $escalas = $query->orderBy('data', 'desc')->paginate(10)->withQueryString();
        
        // --- CORREÇÃO AQUI: Carregar as atividades para o Dropdown ---
        
        // Opção A: Se "atividade" for apenas um campo de texto na tabela 'escalas'
        // Isso pega todos os nomes de atividades únicos que já foram cadastrados
        $atividades = Atividade::all();

        // Opção B: Se você tem uma tabela/Model separado chamado 'Atividade'
        // $atividades = \App\Models\Atividade::orderBy('nome')->pluck('nome');

        // Passa a variável $atividades junto com $escalas
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
            'atividade_id' => 'required|exists:atividades,id',
            'turma' => 'nullable|string', // Se vazio, considera Geral
            'bone_inicial' => 'required|integer', // O nº do boné do Xerife do primeiro dia
        ]);

        $startDate = Carbon::parse($request->data_inicio);
        $endDate = Carbon::parse($request->data_fim);
        $atividadeId = $request->atividade_id;
        $turma = $request->turma;
        $boneInicial = $request->bone_inicial;

        // 1. Buscar Soldados da Turma (ou Geral) ordenados por boné
        $query = Soldado::query();
        if ($turma) {
            $query->where('turma', $turma);
        }
        $soldados = $query->orderBy('numero_bone')->get();

        if ($soldados->isEmpty()) {
            return back()->withErrors(['msg' => 'Nenhum soldado encontrado para esta turma.']);
        }

        // 2. Encontrar o índice do soldado inicial
        $startIndex = $soldados->search(function ($soldado) use ($boneInicial) {
            return $soldado->numero_bone == $boneInicial;
        });

        if ($startIndex === false) {
            return back()->withErrors(['msg' => "Soldado com boné nº $boneInicial não encontrado na turma selecionada."]);
        }

        $totalSoldados = $soldados->count();
        $currentDate = $startDate->copy();
        $createdCount = 0;
        
        // Iterador para controlar o avanço na lista de soldados (0 = Xerife dia 1, 1 = Xerife dia 2...)
        // No dia 1: Indice X é Xerife, X+1 é Sub.
        // No dia 2: Indice X+1 é Xerife, X+2 é Sub.
        $offset = 0; 

        DB::beginTransaction();
        try {
            while ($currentDate->lte($endDate)) {
                // Definir índices circulares
                $indexXerife = ($startIndex + $offset) % $totalSoldados;
                $indexSub = ($startIndex + $offset + 1) % $totalSoldados;

                $xerife = $soldados[$indexXerife];
                $sub = $soldados[$indexSub];

                // 3. Verificar Conflitos (se já estão escalados neste dia em QUALQUER atividade)
                // Nota: O Xerife de hoje será o Sub de amanhã? Se sim, isso gera conflito?
                // A regra diz "não pode reescalar soldados já escalados nas DEMAIS escalas".
                // Assumimos que a verificação é contra outras escalas já salvas no banco para este dia.
                
                $conflitoXerife = DB::table('escala_soldado')
                    ->join('escalas', 'escala_soldado.escala_id', '=', 'escalas.id')
                    ->where('escalas.data', $currentDate->format('Y-m-d'))
                    ->where('escala_soldado.soldado_id', $xerife->id)
                    ->exists();

                $conflitoSub = DB::table('escala_soldado')
                    ->join('escalas', 'escala_soldado.escala_id', '=', 'escalas.id')
                    ->where('escalas.data', $currentDate->format('Y-m-d'))
                    ->where('escala_soldado.soldado_id', $sub->id)
                    ->exists();

                if ($conflitoXerife || $conflitoSub) {
                    // Opcional: Pular o dia ou falhar? 
                    // Como é uma sequência rígida, vamos lançar erro para o usuário corrigir manualmente ou mudar a data.
                    throw new \Exception("Conflito de escala no dia " . $currentDate->format('d/m/Y') . ": " . 
                        ($conflitoXerife ? "Soldado {$xerife->numero_bone} já escalado." : "Soldado {$sub->numero_bone} já escalado."));
                }

                // 4. Criar a Escala
                $escala = Escala::create([
                    'data' => $currentDate->format('Y-m-d'),
                    'atividade_id' => $atividadeId,
                    // 'turma' => $turma // Se a tabela escalas tiver coluna turma, descomente
                ]);

                // 5. Vincular Soldados
                // Assumindo que a tabela pivot não tem coluna de 'função', a ordem de inserção ou lógica define quem é quem.
                // Se houver coluna 'funcao' na pivot, adicione ['funcao' => 'Xerife'] etc.
                $escala->soldados()->attach($xerife->id);
                $escala->soldados()->attach($sub->id);

                $currentDate->addDay();
                $offset++; // No próximo dia, o Xerife será o próximo da lista (que era o Sub hoje)
                $createdCount++;
            }
            
            DB::commit();
            return redirect()->route('escalas.index')->with('success', "$createdCount escalas geradas com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Erro ao gerar escalas: ' . $e->getMessage()]);
        }
    }


}