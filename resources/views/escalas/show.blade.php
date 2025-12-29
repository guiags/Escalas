<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Escala') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('escalas.edit', $escala->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded">
                    ✏️ Editar / Ajustar
                </a>
                
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🖨️ Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 print:hidden">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-300 p-8">
                
                <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                    <h1 class="text-2xl font-bold uppercase tracking-wider">Escala de Serviço</h1>
                    <h3 class="text-xl text-gray-600 mt-2">{{ $escala->atividade->nome }}</h3>
                    <p class="text-lg font-mono mt-1">Data: {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}</p>
                </div>

                <table class="min-w-full border-collapse border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-400 px-4 py-2 text-left w-16">Ord</th>
                            <th class="border border-gray-400 px-4 py-2 text-left">Graduação / Nome de Guerra</th>
                            <th class="border border-gray-400 px-4 py-2 text-left">Turma</th>
                            <th class="border border-gray-400 px-4 py-2 text-center w-32">Assinatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($escala->soldados as $index => $soldado)
                            <tr>
                                <td class="border border-gray-400 px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-400 px-4 py-3 font-bold">
                                    {{ $soldado->graduacao }} {{ $soldado->nome_guerra }}
                                </td>
                                <td class="border border-gray-400 px-4 py-3 text-sm text-gray-600">
                                    {{ $soldado->turma }}
                                </td>
                                <td class="border border-gray-400 px-4 py-3"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-8 text-sm text-gray-500">
                    <p><b>Observações:</b> {{ $escala->observacao }}</p>
                    <p class="mt-2">Carga Horária Computada: {{ $escala->atividade->carga_horaria }} horas.</p>
                </div>

            </div>

            <div class="mt-6 text-center print:hidden">
                <form action="{{ route('escalas.destroy', $escala->id) }}" method="POST" onsubmit="return confirm('Deseja excluir esta escala? As horas serão removidas dos militares.')">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('escalas.index') }}" class="text-blue-600 hover:underline mr-4">Voltar para Lista</a>
                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Excluir Escala</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>