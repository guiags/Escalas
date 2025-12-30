<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Editar Escala Manualmente') }}
            </h2>
            <a href="{{ route('escalas.show', $escala->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Voltar para Visualização
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-lg font-bold text-gray-800">{{ $escala->atividade->nome }}</h3>
                <p class="text-gray-600">Data: {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}</p>
                <p class="mt-2 text-sm text-gray-500">
                    Efetivo Atual: <b>{{ $escala->soldados->count() }}</b> / Meta: <b>{{ $escala->atividade->quantidade_padrao }}</b>
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                        <h4 class="font-bold text-gray-800 mb-4">Adicionar Militar</h4>
                        
                        <form action="{{ route('escalas.adicionarSoldado', $escala->id) }}" method="POST">
                            @csrf
                            
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pesquisar (Nome ou Matrícula):</label>
                                <input type="text" id="search-soldado" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Digite para filtrar...">
                            </div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecione na lista:</label>
                            
                            <select name="soldado_id" id="select-soldado" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-4" required size="10">
                                <option value="" class="text-gray-500">-- Selecione um militar --</option>
                                @foreach($disponiveis as $disponivel)
                                    <option value="{{ $disponivel->id }}">
                                        {{ $disponivel->graduacao }} {{ $disponivel->nome_guerra }} (Mat: {{ $disponivel->matricula }}) - {{ $disponivel->total_horas }}h
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Adicionar na Escala
                            </button>
                        </form>
                        
                        <div class="mt-4 text-xs text-gray-500">
                            * Lista filtrada por sexo compatível com a atividade.
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h4 class="font-bold text-gray-800 mb-4">Militares Escalados ({{ $escala->soldados->count() }})</h4>

                        @if($escala->soldados->isEmpty())
                            <p class="text-gray-500 italic">Nenhum militar nesta escala.</p>
                        @else
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Militar</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Turma</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($escala->soldados as $soldado)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-700">
                                                {{ $soldado->graduacao }} {{ $soldado->nome_guerra }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $soldado->matricula }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $soldado->turma }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $soldado->total_horas }}h
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <form action="{{ route('escalas.removerSoldado', ['escala' => $escala->id, 'soldado' => $soldado->id]) }}" method="POST" onsubmit="return confirm('Remover este militar da escala?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm bg-red-50 hover:bg-red-100 px-3 py-1 rounded">
                                                        Remover
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-soldado');
            const select = document.getElementById('select-soldado');
            
            // Salva todas as opções originais na memória ao carregar a página
            const originalOptions = Array.from(select.options);

            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();

                // Limpa o select atual
                select.innerHTML = '';

                // Filtra e readiciona apenas os que combinam
                const filteredOptions = originalOptions.filter(option => {
                    const text = option.text.toLowerCase();
                    // Mantém a opção padrão (valor vazio) ou se o texto bater com a pesquisa
                    return option.value === "" || text.includes(term);
                });

                // Reconstrói o select
                filteredOptions.forEach(option => {
                    select.appendChild(option);
                });

                // Se não houver resultados, mostra aviso visual (opcional)
                if (filteredOptions.length === 1 && filteredOptions[0].value === "") {
                    // Se só sobrou a opção "Selecione", pode adicionar um aviso
                    // Mas manter simples é melhor.
                }
            });
        });
    </script>
</x-app-layout>