<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerar Escala Automática (Xerife e Subxerife)') }}
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

                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">1. Configurações Gerais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="data_inicio" :value="__('Data Início')" />
                                    <x-text-input id="data_inicio" class="block mt-1 w-full" type="date" name="data_inicio" :value="old('data_inicio')" required />
                                </div>
                                <div>
                                    <x-input-label for="data_fim" :value="__('Data Fim')" />
                                    <x-text-input id="data_fim" class="block mt-1 w-full" type="date" name="data_fim" :value="old('data_fim')" required />
                                </div>
                                <div>
                                    <x-input-label for="turma" :value="__('Turma (Opcional)')" />
                                    <input list="turmas-list" id="turma" name="turma" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Ex: 1 Pelotão">
                                    <datalist id="turmas-list">
                                        @foreach($turmas as $t)
                                            <option value="{{ $t }}">
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium text-blue-600 dark:text-blue-400 mb-4">2. Escala do Xerife</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="atividade_xerife_id" :value="__('Atividade para Xerife')" />
                                    <select id="atividade_xerife_id" name="atividade_xerife_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        @foreach($atividades as $atividade)
                                            <option value="{{ $atividade->id }}">{{ $atividade->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="bone_xerife" :value="__('Nº Boné Inicial (Xerife)')" />
                                    <x-text-input id="bone_xerife" class="block mt-1 w-full" type="number" name="bone_xerife" required placeholder="Ex: 50" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-green-600 dark:text-green-400 mb-4">3. Escala do Subxerife</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="atividade_sub_id" :value="__('Atividade para Subxerife')" />
                                    <select id="atividade_sub_id" name="atividade_sub_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        @foreach($atividades as $atividade)
                                            <option value="{{ $atividade->id }}">{{ $atividade->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="bone_sub" :value="__('Nº Boné Inicial (Subxerife)')" />
                                    <x-text-input id="bone_sub" class="block mt-1 w-full" type="number" name="bone_sub" required placeholder="Ex: 51" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Gerar Escalas') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>