<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Histórico de Escalas') }}
            </h2>
            <a href="{{ route('escalas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Nova Escala
            </a>
            <a href="{{ route('escalas.automacao') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Escala Semanal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <form method="GET" action="{{ route('escalas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        
                        <div>
                            <x-input-label for="data_inicio" :value="__('Data Inicial')" />
                            <x-text-input id="data_inicio" class="block mt-1 w-full" type="date" name="data_inicio" :value="request('data_inicio')" />
                        </div>

                        <div>
                            <x-input-label for="data_fim" :value="__('Data Final')" />
                            <x-text-input id="data_fim" class="block mt-1 w-full" type="date" name="data_fim" :value="request('data_fim')" />
                        </div>

                        <div>
                            <x-input-label for="atividade_id" :value="__('Atividades (Segure Ctrl p/ selecionar várias)')" />
                            <select id="atividade_id" name="atividade_id[]" multiple class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-24">
                                @foreach($atividades as $atividade)
                                    <option value="{{ $atividade->id }}" 
                                        {{ in_array($atividade->id, request('atividade_id', [])) ? 'selected' : '' }}>
                                        {{ $atividade->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2 pb-1">
                            <x-primary-button type="submit" class="h-10">
                                {{ __('Filtrar') }}
                            </x-primary-button>
                            
                            <a href="{{ route('escalas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-10">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>


            <form action="{{ route('escalas.imprimirEmMassa') }}" method="POST" target="_blank" id="form-impressao">
                @csrf

                <div class="bg-white p-4 rounded-t-lg shadow-sm border-b flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Selecionar Todos</span>
                    </div>
                    
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex items-center text-sm disabled:opacity-50 disabled:cursor-not-allowed" id="btn-imprimir">
                        🖨️ Imprimir Selecionados
                    </button>
                </div>

                

                <div class="bg-white overflow-hidden shadow-sm rounded-b-lg">
                    <div class="p-6 text-gray-900">
                        
                        @if($escalas->isEmpty())
                            <div class="text-center text-gray-500 py-8">
                                <p class="text-lg">Nenhuma escala registrada.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($escalas as $escala)
                                    <div class="relative border rounded-lg p-4 shadow-sm border-l-4 border-green-500 bg-white hover:shadow-md transition group">
                                        
                                        <div class="absolute top-3 right-3">
                                            <input type="checkbox" name="escalas_selecionadas[]" value="{{ $escala->id }}" class="escala-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                        </div>

                                        <a href="{{ route('escalas.show', $escala->id) }}" class="block pr-8">
                                            <div class="flex flex-col">
                                                <h3 class="font-bold text-lg text-gray-800 hover:text-green-600">
                                                    {{ $escala->atividade->nome ?? 'Atividade Removida' }}
                                                </h3>
                                                
                                                <p class="text-gray-600 font-mono mt-1">
                                                    {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}
                                                </p>
                                                
                                                <p class="text-xs text-gray-400 capitalize mb-2">
                                                    {{ \Carbon\Carbon::parse($escala->data)->locale('pt_BR')->dayName }}
                                                </p>

                                                <span class="inline-block bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full w-max">
                                                    {{ $escala->soldados->count() }} Militares
                                                </span>
                                            </div>
                                        </a>
                                        
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.escala-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-app-layout>