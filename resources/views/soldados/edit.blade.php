<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Editar Soldado') }}
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

                    <form method="POST" action="{{ route('soldados.update', $soldado->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="matricula" :value="__('Matrícula')" />
                                <x-text-input id="matricula" class="block mt-1 w-full" type="text" name="matricula" :value="old('matricula', $soldado->matricula)" required />
                            </div>

                            <div>
                                <x-input-label for="graduacao" :value="__('Graduação')" />
                                <select name="graduacao" class="block mt-1 w-full rounded-md shadow-sm">
                                    @foreach(['Sd', 'Cb', 'Sgt', 'Ten'] as $grad)
                                        <option value="{{ $grad }}" {{ old('graduacao', $soldado->graduacao) == $grad ? 'selected' : '' }}>
                                            {{ $grad }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="nome_completo" :value="__('Nome Completo')" />
                                <x-text-input id="nome_completo" class="block mt-1 w-full" type="text" name="nome_completo" :value="old('nome_completo', $soldado->nome_completo)" required />
                            </div>

                            <div>
                                <x-input-label for="nome_guerra" :value="__('Nome de Guerra')" />
                                <x-text-input id="nome_guerra" class="block mt-1 w-full" type="text" name="nome_guerra" :value="old('nome_guerra', $soldado->nome_guerra)" required />
                            </div>

                            <div>
                                <x-input-label for="turma" :value="__('Turma')" />
                                <x-text-input id="turma" class="block mt-1 w-full" type="text" name="turma" :value="old('turma', $soldado->turma)" required />
                            </div>

                            <div>
                                <x-input-label for="sexo" :value="__('Sexo')" />
                                <select name="sexo" class="block mt-1 w-full rounded-md">
                                    <option value="M" {{ old('sexo', $soldado->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $soldado->sexo) == 'F' ? 'selected' : '' }}>Feminino</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="numero_bone" :value="__('Nº Boné')" />
                                <x-text-input id="numero_bone" class="block mt-1 w-full" type="text" name="numero_bone" :value="old('numero_bone', $soldado->numero_bone)" />
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <label for="disponivel" class="inline-flex items-center">
                                    <input id="disponivel" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="disponivel" value="1" {{ old('disponivel', $soldado->disponivel) ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Militar disponível para escalas? (Desmarque para Férias/Licença)') }}</span>
                                </label>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('soldados.index') }}" class="text-gray-600 underline mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Atualizar Cadastro') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>