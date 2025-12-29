<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Relatório de Escalas - PMMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.cdnfonts.com/css/rawline');

        @media print {
            @page { 
                margin: 0mm; 
                size: A4 portrait;
            }
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            .assinatura-block { page-break-inside: avoid; }
        }
        
        .font-rawline { font-family: 'Rawline', sans-serif; }
        .bg-bege {
            background-color: #9b8a5c !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="bg-gray-100 print:bg-white text-black font-rawline">

    <div class="no-print fixed top-4 right-4 z-50 flex space-x-2">
        <button onclick="window.print()" class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded shadow-lg">
            🖨️ Imprimir Relatório
        </button>
        <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg">
            Voltar
        </button>
    </div>

    <div class="bg-white w-[210mm] min-h-[297mm] mx-auto shadow-lg print:shadow-none print:m-0 print:w-full relative flex flex-col">
        
        <div class="w-full">
            <img src="{{ asset('images/cabecalho_oficial.png') }}" alt="Cabeçalho Oficial PMMG" class="w-full h-auto block object-cover">
        </div>

        <div class="flex-grow px-[15mm] py-4">

            @php
                $nomesAtividades = $escalas->pluck('atividade.nome')->unique()->implode(' E ');
                $minDate = $escalas->min('data');
                $maxDate = $escalas->max('data');

                // LÓGICA NOVA: Pega observações das ATIVIDADES (remove duplicadas e vazias)
                $observacoesPadrao = $escalas->pluck('atividade.observacao')
                                             ->filter() // Remove vazios
                                             ->unique() // Remove repetidos
                                             ->implode("\n\n");

                $escalasPorData = $escalas->sortBy('data')->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->data)->format('Y-m-d');
                });
            @endphp

            <div class="text-center mb-6 mt-2">
                <h1 class="text-md font-bold uppercase underline decoration-1 underline-offset-2">
                    ESCALA DE {{ $nomesAtividades }}
                </h1>
                <p class="text-sm font-bold mt-1 uppercase">
                    PERÍODO: {{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }} A {{ \Carbon\Carbon::parse($maxDate)->format('d/m/Y') }}
                </p>
            </div>

            @foreach($escalasPorData as $dia => $escalasDoDia)
                
                @if(!$loop->first)
                    <div class="h-6"></div> 
                @endif

                <table class="w-full border-collapse border border-black text-[11px]">
                    <thead>
                        <tr class="bg-bege">
                            <th class="border border-black px-2 py-1 text-center font-bold w-32">DATA</th>
                            <th class="border border-black px-4 py-1 text-left font-bold">MILITAR</th>
                            <th class="border border-black px-2 py-1 text-center font-bold w-20">TURMA</th>
                            <th class="border border-black px-2 py-1 text-center font-bold w-24">MATRÍCULA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($escalasDoDia as $escala)
                            @foreach($escala->soldados as $index => $soldado)
                            <tr>
                                <td class="border border-black px-2 py-1 text-center align-middle bg-gray-50">
                                    {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}<br>
                                    <span class="text-[9px] uppercase text-gray-600">
                                        {{ \Carbon\Carbon::parse($escala->data)->locale('pt_BR')->shortDayName }}
                                    </span>
                                </td>
                                
                                <td class="border border-black px-4 py-2 font-bold uppercase align-middle">
                                    {{ $soldado->graduacao }} {{ $soldado->nome_guerra }}
                                </td>

                                <td class="border border-black px-2 py-1 text-center align-middle">
                                    {{ $soldado->turma }}
                                </td>

                                <td class="border border-black px-2 py-1 text-center align-middle">
                                    {{ $soldado->matricula }}
                                </td>
                            </tr>
                            @endforeach

                            @if($escala->soldados->isEmpty())
                            <tr>
                                <td class="border border-black px-2 py-1 text-center bg-gray-50">
                                    {{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}
                                </td>
                                <td colspan="3" class="border border-black px-4 py-1 text-center italic text-gray-500">
                                    - {{ $escala->atividade->nome }}: Sem Efetivo Lançado -
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endforeach

            @if(!empty($observacoesPadrao))
            <div class="mt-4 text-[12px] border border-black p-2 bg-gray-50 assinatura-block">
                <!--<p class="font-bold underline">OBSERVAÇÕES GERAIS:</p>-->
                <p class="mt-2 whitespace-pre-line">{!! $observacoesPadrao !!}</p>
            </div>
            @endif

            <div class="mt-12 mb-4 text-center assinatura-block">
                <p class="font-bold uppercase text-sm">JOSÉ ARTHUR FIGUEIRAS DEOLINO – CAP PM</p>
                <p class="font-bold uppercase text-sm">CMT DA 231ª CIA ET</p>
            </div>

        </div> 

        <div class="w-full mt-auto">
            <img src="{{ asset('images/rodape_oficial.png') }}" alt="Rodapé Oficial PMMG" class="w-full h-auto block object-cover">
        </div>
        
    </div>

</body>
</html>