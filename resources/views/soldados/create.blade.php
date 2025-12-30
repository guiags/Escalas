<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Soldado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('soldados.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="matricula" :value="__('Matrícula')" />
                                <x-text-input id="matricula" class="block mt-1 w-full" type="text" name="matricula" :value="old('matricula')" required />
                            </div>

                            <div>
                                <x-input-label for="graduacao" :value="__('Graduação')" />
                                <select name="graduacao" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Al Sd">Al Soldado</option>
                                    <option value="Sd">Soldado</option>
                                    <option value="Cb">Cabo</option>
                                    <option value="Sgt">Sargento</option>
                                    <option value="Ten">Tenente</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="nome_completo" :value="__('Nome Completo')" />
                                <x-text-input id="nome_completo" class="block mt-1 w-full" type="text" name="nome_completo" :value="old('nome_completo')" required />
                            </div>

                            <div>
                                <x-input-label for="nome_guerra" :value="__('Nome de Guerra')" />
                                <x-text-input id="nome_guerra" class="block mt-1 w-full" type="text" name="nome_guerra" :value="old('nome_guerra')" required />
                            </div>

                            <div>
                                <x-input-label for="turma" :value="__('Turma (Ex: CFsd 2024)')" />
                                <x-text-input id="turma" class="block mt-1 w-full" type="text" name="turma" :value="old('turma')" required />
                            </div>

                            <div>
                                <x-input-label for="sexo" :value="__('Sexo')" />
                                <select name="sexo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Selecione...</option>
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="numero_bone" :value="__('Nº Boné (Opcional)')" />
                                <x-text-input id="numero_bone" class="block mt-1 w-full" type="text" name="numero_bone" :value="old('numero_bone')" />
                            </div>

                            <div>
                                <x-input-label for="horas_iniciais" :value="__('Horas Iniciais (Banco Anterior)')" />
                                <x-text-input id="horas_iniciais" class="block mt-1 w-full" type="number" name="horas_iniciais" :value="old('horas_iniciais', $soldado->horas_iniciais ?? 0)" />
                                <p class="text-xs text-gray-500 mt-1">Saldo de horas trazido de outros sistemas (usado para desempate).</p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Salvar Cadastro') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>