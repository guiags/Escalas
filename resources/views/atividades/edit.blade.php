<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Editar Atividade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('atividades.update', $atividade->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-2">
                                <x-input-label for="nome" :value="__('Nome da Atividade')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $atividade->nome)" required />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sexo_restrito" :value="__('Restrição de Sexo')" />
                                <select name="sexo_restrito" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" {{ is_null($atividade->sexo_restrito) ? 'selected' : '' }}>Indiferente (Ambos podem)</option>
                                    <option value="M" {{ $atividade->sexo_restrito === 'M' ? 'selected' : '' }}>Somente Masculino</option>
                                    <option value="F" {{ $atividade->sexo_restrito === 'F' ? 'selected' : '' }}>Somente Feminino</option>
                                </select>
                                <x-input-error :messages="$errors->get('sexo_restrito')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="quantidade_padrao" :value="__('Quantidade de Militares')" />
                                <x-text-input id="quantidade_padrao" class="block mt-1 w-full" type="number" name="quantidade_padrao" :value="old('quantidade_padrao', $atividade->quantidade_padrao)" min="1" required />
                                <x-input-error :messages="$errors->get('quantidade_padrao')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="carga_horaria" :value="__('Carga Horária (em horas)')" />
                                <x-text-input id="carga_horaria" class="block mt-1 w-full" type="number" name="carga_horaria" :value="old('carga_horaria', $atividade->carga_horaria)" min="0" required />
                                <x-input-error :messages="$errors->get('carga_horaria')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <x-input-label for="observacao" :value="__('Observações Padrão (Instruções do Serviço)')" />
                                <textarea id="observacao" name="observacao" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('observacao', $atividade->observacao) }}</textarea>
                                <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('atividades.index') }}" class="text-gray-600 underline mr-4">Cancelar</a>
                            <x-primary-button class="bg-amber-600 hover:bg-amber-700">
                                {{ __('Atualizar Atividade') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>