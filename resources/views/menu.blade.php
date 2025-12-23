<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Sistema de Escalas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-caqui overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-grey">
                    <p class="mb-6 text-lg">
                        Bem-vindo(a) <b>Al Sd {{ Auth::user()->name }}</b>! Escolha uma das opções abaixo para gerenciar o sistema.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <a href="{{ route('soldados.index') }}" class="block">
                            <div class="p-6 bg-blue-100 border-l-4 border-blue-500 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1">
                                <h3 class="text-2xl font-bold text-blue-800 mb-2">
                                    &#x1F46E; Soldados
                                </h3>
                                <p class="text-blue-700">
                                    Cadastro, visualização e edição dos dados de todos os policiais (Número de Polícia, Nome de Guerra, etc.).
                                </p>
                                <span class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:text-blue-900">
                                    Acessar &rarr;
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('escalas.index') }}" class="block">
                            <div class="p-6 bg-green-100 border-l-4 border-green-500 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1">
                                <h3 class="text-2xl font-bold text-green-800 mb-2">
                                    &#x1F4C5; Escalas
                                </h3>
                                <p class="text-green-700">
                                    Criação, visualização e atribuição de policiais aos serviços, turnos e datas das escalas.
                                </p>
                                <span class="mt-4 inline-block text-sm font-semibold text-green-600 hover:text-green-900">
                                    Acessar &rarr;
                                </span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>