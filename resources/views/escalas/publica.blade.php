<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escala Individual - {{ $soldado->nome_guerra }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-caqui-custom { background-color: #e3dcd3; } /* Cor bege claro militar */
        .text-caqui-dark { color: #4a4538; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    
    <nav class="bg-caqui bg-caqui-custom border-b border-gray-300 dark:border-gray-700 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20"> <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="flex items-center gap-3">
                            <img src="{{ asset('images/Logo231.png') }}" alt="Logo" class="block h-14 w-auto drop-shadow-sm" />
                            
                            <div class="hidden md:block">
                                <span class="block font-bold text-xl text-gray-800 dark:text-white leading-none">
                                    Escala de Serviço
                                </span>
                                <span class="block text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Batalhão de Comando e Serviços
                                </span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Área Restrita
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-10">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8"> <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8 border-l-8 border-indigo-600">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight">
                                {{ $soldado->posto_graduacao }} {{ $soldado->nome_guerra }}
                            </h2>
                            <div class="mt-2 flex items-center text-gray-600 dark:text-gray-400 space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    Matrícula: <strong>{{ $soldado->matricula }}</strong>
                                </span>
                                <span class="hidden md:inline">|</span>
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Turma: <strong>{{ $soldado->turma }}</strong>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4 md:mt-0 flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1 shadow-inner">
                            <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $mesAnterior]) }}" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 rounded-md shadow-sm transition">
                                &larr; Anterior
                            </a>
                            <span class="px-6 py-2 font-bold text-gray-800 dark:text-white uppercase min-w-[140px] text-center flex items-center justify-center border-x border-gray-300 dark:border-gray-600 mx-1">
                                {{ $dataBase->translatedFormat('F Y') }}
                            </span>
                            <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $proximoMes]) }}" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 rounded-md shadow-sm transition">
                                Próximo &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-300 dark:border-gray-700">
                
                <div class="grid grid-cols-7 bg-gray-200 dark:bg-gray-900 border-b-2 border-gray-300 dark:border-gray-600">
                    @foreach(['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO'] as $index => $dia)
                        <div class="py-3 text-center text-xs md:text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest {{ $index == 0 || $index == 6 ? 'bg-gray-300/50 dark:bg-black/20' : '' }}">
                            {{ $dia }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 bg-gray-300 dark:bg-gray-600 gap-px border-l border-t border-gray-300 dark:border-gray-600">
                    
                    {{-- Espaços vazios início --}}
                    @for ($i = 0; $i < $inicioMes->dayOfWeek; $i++)
                        <div class="bg-gray-50 dark:bg-gray-800 min-h-[140px]"></div>
                    @endfor

                    {{-- Loop dos Dias --}}
                    @for ($day = 1; $day <= $fimMes->day; $day++)
                        @php
                            $currentDate = $inicioMes->copy()->addDays($day - 1);
                            $dateString = $currentDate->format('Y-m-d');
                            $escalaDia = $escalas->get($dateString);
                            $isToday = $dateString === now()->format('Y-m-d');
                            $isWeekend = $currentDate->isWeekend();
                        @endphp

                        <div class="relative bg-white dark:bg-gray-800 min-h-[150px] p-2 transition hover:bg-indigo-50/30 dark:hover:bg-gray-700 flex flex-col {{ $isWeekend ? 'bg-gray-50/60 dark:bg-gray-800/80' : '' }}">
                            
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-lg font-bold {{ $isToday ? 'bg-indigo-600 text-white w-8 h-8 flex items-center justify-center rounded-full shadow-md ring-2 ring-indigo-200' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $day }}
                                </span>
                                @if($isToday)
                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">Hoje</span>
                                @endif
                            </div>

                            @if($escalaDia)
                                <div class="flex-grow flex flex-col justify-center animate-pulse-once">
                                    <div class="w-full p-3 rounded-md shadow-sm border-l-[6px] 
                                        {{ str_contains(strtolower($escalaDia->atividade->nome), 'vermelha') ? 'bg-red-100 border-red-600 text-red-900 dark:bg-red-900/40 dark:text-red-100' : 'bg-indigo-100 border-indigo-600 text-indigo-900 dark:bg-indigo-900/40 dark:text-indigo-100' }}">
                                        
                                        <div class="font-black text-sm md:text-base leading-tight break-words uppercase">
                                            {{ $escalaDia->atividade->nome }}
                                        </div>
                                        
                                        @if($escalaDia->turno)
                                            <div class="mt-2 text-xs font-bold px-2 py-0.5 rounded inline-block bg-white/50 dark:bg-black/20">
                                                {{ $escalaDia->turno }}
                                            </div>
                                        @endif
                                        
                                        @if($escalaDia->observacao && $escalaDia->observacao !== 'Gerada automaticamente.')
                                            <p class="mt-1 text-[10px] opacity-75 italic truncate">
                                                {{ $escalaDia->observacao }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex-grow flex items-center justify-center opacity-10">
                                    <span class="text-4xl text-gray-300 dark:text-gray-600 font-thin">-</span>
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
                        <div class="bg-gray-50 dark:bg-gray-800 min-h-[140px]"></div>
                    @endfor
                </div>
            </div>

            <div class="mt-8 text-center border-t border-gray-200 dark:border-gray-700 pt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Documento gerado eletronicamente em {{ now()->format('d/m/Y H:i') }}. 
                    <br>Sistema de Gestão de Escalas - Uso Interno e Público.
                </p>
            </div>
        </div>
    </div>

</body>
</html>