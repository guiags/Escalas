<x-guest-layout>
    <nav x-data="{ open: false }" class="bg-[#F5EFE6] dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="/">
                            <x-application-logo class="block h-10 w-auto fill-current text-indigo-800 dark:text-white" />
                        </a>
                        <span class="ml-3 font-semibold text-xl text-indigo-800 dark:text-white tracking-tight">Escala Online</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-400 focus:bg-indigo-700 dark:focus:bg-indigo-400 active:bg-indigo-900 dark:active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Área do Militar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 dark:bg-gray-700/50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="font-bold text-2xl text-indigo-900 dark:text-white">
                                {{ $soldado->posto_graduacao }} {{ $soldado->nome_guerra }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Matrícula: <span class="font-medium">{{ $soldado->matricula }}</span> | 
                                Turma: <span class="font-medium">{{ $soldado->turma }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-900 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                    <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $mesAnterior]) }}" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 dark:text-gray-300 dark:hover:bg-gray-700 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wide">
                        {{ $dataBase->translatedFormat('F Y') }}
                    </h3>
                    
                    <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $proximoMes]) }}" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 dark:text-gray-300 dark:hover:bg-gray-700 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="grid grid-cols-7 bg-gray-100 dark:bg-gray-900/80 text-center py-3 border-b border-gray-200 dark:border-gray-700">
                        @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $dia)
                            <div class="text-xs font-bold text-gray-500 uppercase">{{ $dia }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 bg-gray-200 dark:bg-gray-700 gap-px">
                        {{-- Espaços vazios início --}}
                        @for ($i = 0; $i < $inicioMes->dayOfWeek; $i++)
                            <div class="bg-white dark:bg-gray-800 min-h-[120px]"></div>
                        @endfor

                        {{-- Dias do Mês --}}
                        @for ($day = 1; $day <= $fimMes->day; $day++)
                            @php
                                $currentDate = $inicioMes->copy()->addDays($day - 1);
                                $dateString = $currentDate->format('Y-m-d');
                                $escalaDia = $escalas->get($dateString);
                                $isToday = $dateString === now()->format('Y-m-d');
                            @endphp

                            <div class="bg-white dark:bg-gray-800 min-h-[120px] p-2 transition hover:bg-gray-50 dark:hover:bg-gray-700/50 relative group border-r border-b border-gray-100 dark:border-gray-700/20 {{ ($inicioMes->dayOfWeek + $day - 1) % 7 === 0 || ($inicioMes->dayOfWeek + $day - 1) % 7 === 6 ? 'bg-gray-50/50 dark:bg-gray-800/80' : '' }}">
                                
                                <span class="text-sm font-medium absolute top-2 left-2 {{ $isToday ? 'bg-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-sm' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $day }}
                                </span>

                                @if($escalaDia)
                                    <div class="mt-8 h-full flex flex-col justify-center">
                                        <div class="p-3 rounded-lg shadow-sm border-l-[6px] transition transform hover:scale-[1.02] cursor-pointer
                                            {{ str_contains(strtolower($escalaDia->atividade->nome), 'vermelha') ? 'bg-red-50 border-red-500 hover:shadow-red-100' : 'bg-indigo-50 border-indigo-500 hover:shadow-indigo-100' }}">
                                            
                                            <p class="font-extrabold text-base leading-tight text-gray-900 truncate" title="{{ $escalaDia->atividade->nome }}">
                                                {{ $escalaDia->atividade->nome }}
                                            </p>
                                            
                                            @if($escalaDia->turno)
                                                <p class="mt-1.5 text-sm font-medium {{ str_contains(strtolower($escalaDia->atividade->nome), 'vermelha') ? 'text-red-700' : 'text-indigo-700' }}">
                                                    {{ $escalaDia->turno }}
                                                </p>
                                            @endif

                                            <p class="mt-2 text-xs font-semibold opacity-60 uppercase tracking-wide">
                                                {{ \Carbon\Carbon::parse($escalaDia->data)->translatedFormat('l') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endfor

                        {{-- Espaços vazios final --}}
                        @php
                            $remainingDays = 7 - (($inicioMes->dayOfWeek + $fimMes->day) % 7);
                            if ($remainingDays == 7) $remainingDays = 0;
                        @endphp
                        @for ($i = 0; $i < $remainingDays; $i++)
                            <div class="bg-white dark:bg-gray-800 min-h-[120px]"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400 pb-10">
                <p>Sistema de Escalas &copy; {{ date('Y') }} - Batalhão de Comando e Serviços</p>
            </div>
        </div>
    </div>
</x-guest-layout>