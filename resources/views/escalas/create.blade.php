<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Nova Escala de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-10 bg-white border-b border-gray-200">

                    <div class="mb-4">
                        <a href="{{ route('escalas.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Voltar para o Gerenciamento de Escalas
                        </a>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        Definir os detalhes do novo serviço
                    </h3>

                    <form method="POST" action="{{ route('escalas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <x-input-label for="data" :value="__('Data do Serviço')" />
                                <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('data')" />
                            </div>

                            <div>
                                <x-input-label for="turno" :value="__('Turno')" />
                                <select id="turno" name="turno" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">Selecione o Turno</option>
                                    <option value="manha" {{ old('turno') == 'manha' ? 'selected' : '' }}>Manhã (Ex: 08:00 - 14:00)</option>
                                    <option value="tarde" {{ old('turno') == 'tarde' ? 'selected' : '' }}>Tarde (Ex: 14:00 - 20:00)</option>
                                    <option value="noite" {{ old('turno') == 'noite' ? 'selected' : '' }}>Noite (Ex: 20:00 - 08:00)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('turno')" />
                            </div>

                            <div>
                                <x-input-label for="vagas_necessarias" :value="__('Vagas Necessárias (Efetivo)')" />
                                <x-text-input id="vagas_necessarias" class="block mt-1 w-full" type="number" name="vagas_necessarias" :value="old('vagas_necessarias')" required min="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('vagas_necessarias')" />
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <x-input-label for="servico" :value="__('Tipo de Serviço / Função')" />
                            <x-text-input id="servico" class="block mt-1 w-full" type="text" name="servico" :value="old('servico')" required placeholder="Ex: Patrulhamento Motorizado, Atendimento ao Público, Administrativo" />
                            <x-input-error class="mt-2" :messages="$errors->get('servico')" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Criar Escala') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>