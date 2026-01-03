<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Histórico de Escalas') }}
            </h2>

            <div class="flex gap-4">
                <a href="{{ route('escalas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Nova Escala
                </a>
                <a href="{{ route('escalas.automacao') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Escala Semanal
                </a>
            </div>
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

                        <<div>
    <x-input-label for="atividade_id" :value="__('Atividades (Selecione múltiplas)')" />

        <div x-data="dropdownAtividades(@json(request('atividade_id', [])))" class="relative mt-1">

            <button type="button" 
                    @click="toggleOpen()" 
                    @click.outside="open = false"
                    class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 h-10">
                
                <span class="block truncate" x-text="displayText"></span>
                
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>

            <div x-show="open" 
                x-transition
                style="display: none;"
                class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm dark:bg-gray-800">

                @foreach($atividades as $atividade)
                    <div @click="toggleItem({{ $atividade->id }})" 
                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-gray-200">
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 mr-3 pointer-events-none"
                                :checked="isSelected({{ $atividade->id }})">
                            
                            <span class="block truncate"
                                :class="{ 'font-semibold': isSelected({{ $atividade->id }}) }">
                                {{ $atividade->nome }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <template x-for="id in selected" :key="id">
                <input type="hidden" name="atividade_id[]" :value="id">
            </template>
            </div>
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