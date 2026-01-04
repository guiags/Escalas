<x-app-layout>
<body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-10">
        
        <div class="w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $soldado->posto_graduacao }} {{ $soldado->nome_guerra }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Matrícula: {{ $soldado->matricula }} | Turma: {{ $soldado->turma }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-4 sm:mt-0 bg-white dark:bg-gray-800 rounded-lg p-1 shadow">
                    <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $mesAnterior]) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <span class="px-4 font-semibold uppercase text-sm w-32 text-center select-none">
                        {{ $dataBase->translatedFormat('F Y') }}
                    </span>
                    <a href="{{ route('escala.publica', ['matricula' => $soldado->matricula, 'mes' => $proximoMes]) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                
                <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-center py-3">
                    @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $dia)
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $dia }}</div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 bg-gray-200 dark:bg-gray-700 gap-px">
                    {{-- Loop para preencher espaços vazios antes do dia 1 --}}
                    @for ($i = 0; $i < $inicioMes->dayOfWeek; $i++)
                        <div class="bg-white dark:bg-gray-800 min-h-[100px] sm:min-h-[120px]"></div>
                    @endfor

                    {{-- Loop dos dias do mês --}}
                    @for ($day = 1; $day <= $fimMes->day; $day++)
                        @php
                            $currentDate = $inicioMes->copy()->addDays($day - 1);
                            $dateString = $currentDate->format('Y-m-d');
                            $escalaDia = $escalas->get($dateString);
                            $isToday = $dateString === now()->format('Y-m-d');
                        @endphp

                        <div class="bg-white dark:bg-gray-800 min-h-[100px] sm:min-h-[120px] p-2 transition hover:bg-gray-50 dark:hover:bg-gray-700/50 relative group">
                            
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium {{ $isToday ? 'bg-indigo-600 text-white w-6 h-6 flex items-center justify-center rounded-full shadow-md' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $day }}
                                </span>
                            </div>

                            @if($escalaDia)
                                <div class="mt-2">
                                    <div class="p-2 rounded-md border-l-4 shadow-sm text-xs
                                        {{ str_contains(strtolower($escalaDia->atividade->nome), 'vermelha') ? 'bg-red-50 border-red-500 text-red-700 dark:bg-red-900/30 dark:text-red-200' : 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' }}">
                                        
                                        <p class="font-bold truncate">{{ $escalaDia->atividade->nome }}</p>
                                        
                                        @if($escalaDia->turno)
                                            <p class="mt-0.5 opacity-80">{{ $escalaDia->turno }}</p>
                                        @endif

                                        <p class="mt-1 text-[10px] opacity-70">
                                            {{ \Carbon\Carbon::parse($escalaDia->data)->translatedFormat('l') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endfor

                    {{-- Preencher espaços vazios no final --}}
                    @php
                        $remainingDays = 7 - (($inicioMes->dayOfWeek + $fimMes->day) % 7);
                        if ($remainingDays == 7) $remainingDays = 0;
                    @endphp
                    @for ($i = 0; $i < $remainingDays; $i++)
                        <div class="bg-white dark:bg-gray-800 min-h-[100px] sm:min-h-[120px]"></div>
                    @endfor
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400 pb-10">
                <p>Sistema de Escalas &copy; {{ date('Y') }}</p>
                <a href="{{ route('login') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-500 hover:underline">
                    Acesso Administrativo
                </a>
            </div>
        </div>
    </div>
</body>
</x-app-layout>