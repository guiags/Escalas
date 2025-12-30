<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Menu Principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <p class="text-lg">
                        Bem-vindo(a), AL SD <b>{{ Auth::user()->name }}</b>! Use os cartões abaixo para navegar.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <a href="{{ route('soldados.index') }}" class="block group">
                    <div class="h-full p-6 bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-blue-900">Soldados</h3>
                            <span class="text-3xl">👮</span>
                        </div>
                        <p class="text-blue-700 text-sm">
                            Gerencie o efetivo, atualize dados cadastrais, patentes e status de disponibilidade.
                        </p>
                        <div class="mt-4 text-blue-800 font-semibold group-hover:underline">
                            Acessar Efetivo &rarr;
                        </div>
                    </div>
                </a>

                <a href="{{ route('atividades.index') }}" class="block group">
                    <div class="h-full p-6 bg-amber-50 border-l-4 border-amber-500 rounded-lg shadow hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-amber-900">Atividades</h3>
                            <span class="text-3xl">⚙️</span>
                        </div>
                        <p class="text-amber-800 text-sm">
                            Cadastre os tipos de serviço (Sentinela, Limpeza), regras de sexo e carga horária.
                        </p>
                        <div class="mt-4 text-amber-900 font-semibold group-hover:underline">
                            Configurar Atividades &rarr;
                        </div>
                    </div>
                </a>

                <a href="{{ route('escalas.index') }}" class="block group">
                    <div class="h-full p-6 bg-green-50 border-l-4 border-green-600 rounded-lg shadow hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-green-900">Escalas</h3>
                            <span class="text-3xl">📅</span>
                        </div>
                        <p class="text-green-800 text-sm">
                            Gere novas escalas automáticas, visualize o histórico e imprima os serviços diários.
                        </p>
                        <div class="mt-4 text-green-900 font-semibold group-hover:underline">
                            Gerenciar Escalas &rarr;
                        </div>
                    </div>
                </a>

                <a href="{{ route('gerar_certificado') }}" class="block group">
                    <div class="h-full p-6 bg-red-50 border-l-4 border-red-600 rounded-lg shadow hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-red-900">Certificados</h3>
                            <span class="text-3xl">🎉</span>
                        </div>
                        <p class="text-red-800 text-sm">
                            Gere Certificados para o discente, referente aos cartões de aniversário.
                        </p>
                        <div class="mt-4 text-red-900 font-semibold group-hover:underline">
                            Imprimir Certificado &rarr;
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>