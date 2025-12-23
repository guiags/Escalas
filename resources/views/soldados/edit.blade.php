// resources/views/soldados/edit.blade.php

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Soldado: ') . $soldado->nome_guerra }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-10 bg-white border-b border-gray-200">

                    <div class="mb-4">
                        <a href="{{ route('soldados.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Voltar para a Lista
                        </a>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        Atualizar dados:
                    </h3>

                    <form method="POST" action="{{ route('soldados.update', $soldado) }}">
                        @csrf
                        @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="numero_policia" :value="__('Número de Polícia (RG)')" />
                                <x-text-input 
                                    id="numero_policia" 
                                    class="block mt-1 w-full" 
                                    type="text" 
                                    name="numero_policia" 
                                    :value="old('numero_policia', $soldado->numero_policia)" required autofocus 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('numero_policia')" />
                            </div>

                            <div>
                                <x-input-label for="numero_curso" :value="__('Número de Curso')" />
                                <x-text-input 
                                    id="numero_curso" 
                                    class="block mt-1 w-full" 
                                    type="text" 
                                    name="numero_curso" 
                                    :value="old('numero_curso', $soldado->numero_curso)" />
                                <x-input-error class="mt-2" :messages="$errors->get('numero_curso')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="nome_guerra" :value="__('Nome de Guerra')" />
                            <x-text-input 
                                id="nome_guerra" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="nome_guerra" 
                                :value="old('nome_guerra', $soldado->nome_guerra)" required 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('nome_guerra')" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="nome_completo" :value="__('Nome Completo')" />
                            <x-text-input 
                                id="nome_completo" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="nome_completo" 
                                :value="old('nome_completo', $soldado->nome_completo)" required 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('nome_completo')" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4 bg-yellow-500 hover:bg-yellow-600">
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>