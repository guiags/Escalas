<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Configuração de Atividades') }}
            </h2>
            <a href="{{ route('atividades.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded">
                + Nova Atividade
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Nome da Atividade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Restrição de Sexo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Efetivo Padrão</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Carga Horária</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($atividades as $atividade)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $atividade->nome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($atividade->sexo_restrito === 'M')
                                                <span class="text-blue-600 font-bold">Masculino Apenas</span>
                                            @elseif($atividade->sexo_restrito === 'F')
                                                <span class="text-pink-600 font-bold">Feminino Apenas</span>
                                            @else
                                                <span class="text-green-600">Ambos (Indiferente)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $atividade->quantidade_padrao }} militares
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $atividade->carga_horaria }} horas
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('atividades.edit', $atividade->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                            
                                            <form action="{{ route('atividades.destroy', $atividade->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza? Isso pode afetar escalas antigas.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Nenhuma atividade cadastrada ainda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>