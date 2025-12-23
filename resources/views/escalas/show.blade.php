<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerenciar Escala: ') . $escala->servico }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Detalhes do Serviço:</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}</p>
                    <p><strong>Turno:</strong> <span class="capitalize">{{ $escala->turno }}</span></p>
                    <p><strong>Serviço:</strong> {{ $escala->servico }}</p>
                </div>
                @php
                    $atribuidos = $escala->soldados->count();
                    $necessarios = $escala->vagas_necessarias;
                    $restantes = $necessarios - $atribuidos;
                    $cor_status = $atribuidos < $necessarios ? 'text-red-600' : 'text-green-600';
                @endphp
                <p class="mt-4 text-lg font-semibold {{ $cor_status }}">
                    Efetivo: {{ $atribuidos }} de {{ $necessarios }} vagas preenchidas.
                    @if ($restantes > 0)
                        (Faltam {{ $restantes }} soldados)
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 h-fit">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Atribuir Soldado</h3>
                    
                    <form method="POST" action="{{ route('escalas.attachSoldier', $escala) }}">
                        @csrf
                        
                        <div>
                            <x-input-label for="soldado_id" :value="__('Selecione o Soldado')" />
                            
                            <select id="soldado_id" name="soldado_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">--- Selecionar ---</option>
                                @foreach ($soldadosDisponiveis as $soldado)
                                    <option value="{{ $soldado->id }}">{{ $soldado->nome_guerra }} ({{ $soldado->numero_policia }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('soldado_id')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Adicionar à Escala') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Soldados Escalados</h3>

                    @if ($escala->soldados->isEmpty())
                        <p class="text-gray-500">Nenhum soldado atribuído a esta escala ainda.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome de Guerra</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N.º Polícia</th>
                                        <th class="px-6 py-3">Remover</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($escala->soldados as $soldado)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $soldado->nome_guerra }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $soldado->numero_policia }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('escalas.detachSoldier', $escala) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="soldado_id" value="{{ $soldado->id }}">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Confirmar remoção de {{ $soldado->nome_guerra }} desta escala?')">
                                                        Remover
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>