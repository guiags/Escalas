<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerar Escala Automática (Xerife/Subxerife)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Ops!</strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('escalas.storeAutomacao') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="data_inicio" :value="__('Data Início')" />
                                <x-text-input id="data_inicio" class="block mt-1 w-full" type="date" name="data_inicio" :value="old('data_inicio')" required />
                            </div>

                            <div>
                                <x-input-label for="data_fim" :value="__('Data Fim')" />
                                <x-text-input id="data_fim" class="block mt-1 w-full" type="date" name="data_fim" :value="old('data_fim')" required />
                            </div>

                            <div>
                                <x-input-label for="atividade_id" :value="__('Tipo de Escala (Atividade)')" />
                                <select id="atividade_id" name="atividade_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach($atividades as $atividade)
                                        <option value="{{ $atividade->id }}">{{ $atividade->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="turma" :value="__('Turma (Deixe em branco para Geral)')" />
                                <input list="turmas-list" id="turma" name="turma" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Ex: 1 Pelotão ou deixe vazio">
                                <datalist id="turmas-list">
                                    @foreach($turmas as $t)
                                        <option value="{{ $t }}">
                                    @endforeach
                                </datalist>
                                <p class="text-sm text-gray-500 mt-1">Se preenchido, usará apenas soldados desta turma. Se vazio, usará todos.</p>
                            </div>

                            <div>
                                <x-input-label for="bone_inicial" :value="__('Número do Boné do Xerife (Dia 1)')" />
                                <x-text-input id="bone_inicial" class="block mt-1 w-full" type="number" name="bone_inicial" required placeholder="Ex: 50" />
                                <p class="text-sm text-gray-500 mt-1">A escala começará com este soldado e o próximo (Ex: 50 e 51). No dia seguinte será 51 e 52.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Gerar Escalas Automaticamente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>