<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gerar Nova Escala') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('escalas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="data" :value="__('Data da Escala')" />
                                <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data')" required />
                            </div>

                            <div>
                                <x-input-label for="atividade_id" :value="__('Tipo de Serviço')" />
                                <select name="atividade_id" id="atividade_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione...</option>
                                    @foreach($atividades as $atividade)
                                        <option value="{{ $atividade->id }}" {{ old('atividade_id') == $atividade->id ? 'selected' : '' }}>
                                            {{ $atividade->nome }} 
                                            (Carga: {{ $atividade->carga_horaria }}h)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 p-5 rounded-lg border border-gray-200 ">
                                <span class="block text-base font-semibold text-gray-800 mb-3">Como deseja gerar esta escala?</span>
                                
                                <div class="flex items-start mb-4">
                                    <div class="flex items-center h-5">
                                        <input id="modo_auto" type="radio" value="automatico" name="modo_geracao" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" {{ old('modo_geracao', 'automatico') == 'automatico' ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="modo_auto" class="font-medium text-gray-900">Automático (Recomendado)</label>
                                        <p class="text-gray-500">O sistema seleciona os militares com menos horas nesta atividade e tenta diversificar as turmas.</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="modo_manual" type="radio" value="manual" name="modo_geracao" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('modo_geracao') == 'manual' ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="modo_manual" class="font-medium text-gray-900">Manual (Escala Vazia)</label>
                                        <p class="text-gray-500">Cria uma escala em branco para você adicionar nomes manualmente. Ideal para <b>Xerife</b>, <b>Sub-Xerife</b> ou trocas específicas.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        
                            
                        <div class="grid grid-cols-1 md:grid-cols-1 mt-5">
                            <x-input-label for="turno" :value="__('Turno')" />
                            <textarea id="turno" name="turno" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ex: Informações do Turno."></textarea>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('escalas.index') }}" class="text-gray-600 underline mr-4">Cancelar</a>
                            
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Criar Escala') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>