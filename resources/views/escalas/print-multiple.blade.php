<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Relatório de Escalas - PMMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.cdnfonts.com/css/rawline');

        /* ================= ESTILOS DE IMPRESSÃO ================= */
        @media print {
            @page {
                margin: 0; 
                size: A4 portrait;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print { display: none !important; }

            /* CABEÇALHO FIXO */
            .fixed-header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 140px; 
                z-index: 1000;
                background-color: white;
                overflow: hidden;
            }
            
            .fixed-header img {
                width: 100%;
                height: 100%;
                object-fit: fill; 
            }

            /* RODAPÉ FIXO */
            .fixed-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100px;
                z-index: 1000;
                background-color: white;
            }
            .fixed-footer img {
                width: 100%;
                height: 100%;
                object-fit: contain; 
            }

            /* TABELA DE ESPAÇAMENTO */
            table.report-container {
                width: 100%;
                border-collapse: collapse;
            }

            thead.report-header { display: table-header-group; }
            tfoot.report-footer { display: table-footer-group; }

            .header-space { height: 170px; } 
            .footer-space { height: 110px; } 
            
            tr { page-break-inside: avoid; }
            .assinatura-block { page-break-inside: avoid; }

        }
        /* ================= FIM DO CSS DE IMPRESSÃO ================= */

        .font-rawline { font-family: 'Rawline', sans-serif; }
        
        .bg-bege {
            background-color: #9b8a5c !important;
            color: black !important;
        }

        /* TELA */
        @media screen {
            .fixed-header, .fixed-footer { display: none; }
            .screen-header { display: block; margin-bottom: 0px; }
            .screen-footer { display: block; margin-top: 50px; }
            body { padding: 20px; background: #f3f4f6; }
            .report-container { background: white; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto; max-width: 210mm; }
        }
    </style>
</head>
<body class="font-rawline text-black">

    <div class="no-print fixed top-4 right-4 z-50 flex space-x-2">
        <button onclick="window.print()" class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded shadow-lg">
            🖨️ Imprimir
        </button>
        <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg">
            Voltar
        </button>
    </div>

    <div class="fixed-header hidden print:block">
        <img src="{{ asset('images/cabecalho_oficial.png') }}" alt="Cabeçalho">
    </div>

    <div class="fixed-footer hidden print:block">
        <img src="{{ asset('images/rodape_oficial.png') }}" alt="Rodapé">
    </div>

    <table class="report-container mx-auto">
        
        <thead class="report-header">
            <tr>
                <td>
                    <div class="header-space hidden print:block">&nbsp;</div>
                    <div class="screen-header print:hidden">
                        <img src="{{ asset('images/cabecalho_oficial.png') }}" class="w-full h-auto">
                    </div>
                </td>
            </tr>
        </thead>

        <tfoot class="report-footer">
            <tr>
                <td>
                    <div class="footer-space hidden print:block">&nbsp;</div>
                    <div class="screen-footer print:hidden">
                        <img src="{{ asset('images/rodape_oficial.png') }}" class="w-full h-auto">
                    </div>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <tr>
                <td class="px-[10mm] py-4 align-top">
                    
                    @php
                        $nomesAtividades = $escalas->pluck('atividade.nome')->unique()->implode(' E ');
                        $minDate = $escalas->min('data');
                        $maxDate = $escalas->max('data');
                        
                        $observacoesPadrao = $escalas->pluck('atividade.observacao')
                                                     ->filter()
                                                     ->unique()
                                                     ->map(function($obs) {
                                                         return nl2br($obs);
                                                     })
                                                     ->implode("<br><br>");

                        $escalasPorData = $escalas->sortBy('data')->groupBy(fn($item) => \Carbon\Carbon::parse($item->data)->format('Y-m-d'));
                    @endphp

                    <div class="text-center mb-6">
                        <h1 class="text-md font-bold uppercase underline decoration-1 underline-offset-2">
                            ESCALA DE {{ $nomesAtividades }}
                        </h1>
                        <p class="text-sm font-bold mt-1 uppercase">
                            PERÍODO: {{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }} A {{ \Carbon\Carbon::parse($maxDate)->format('d/m/Y') }}
                        </p>
                    </div>

                    @foreach($escalasPorData as $dia => $escalasDoDia)
                        
                        @if(!$loop->first) <div class="h-6"></div> @endif

                        <table class="w-full border-collapse border border-black text-[11px]">
                            <thead>
                                <tr class="bg-bege">
                                    <th class="border border-black px-2 py-1 text-center font-bold w-24">DATA</th>
                                    
                                    <th class="border border-black px-2 py-1 text-center font-bold w-24">ATIVIDADE</th>
                                    <th class="border border-black px-2 py-1 text-center font-bold w-24">MATRÍCULA</th>
                                    <th class="border border-black px-2 py-1 text-left font-bold w-[35%]">MILITAR</th>
                                    
                                    <th class="border border-black px-2 py-1 text-center font-bold w-16">TURMA</th>
                                    
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
                                        
                                        <td class="border border-black px-2 py-1 text-center align-middle text-[10px] uppercase font-bold">
                                            {{ $escala->atividade->nome }}
                                        </td>

                                        <td class="border border-black px-2 py-1 text-center align-middle">{{ $soldado->matricula }}</td>

                                        <td class="border border-black px-2 py-2 font-bold uppercase align-middle">
                                            {{ $soldado->graduacao }} {{ $soldado->nome_guerra }}
                                        </td>
                                        
                                        <td class="border border-black px-2 py-1 text-center align-middle">{{ $soldado->turma }}</td>
                                        
                                        
                                    </tr>
                                    @endforeach

                                    @if($escala->soldados->isEmpty())
                                    <tr>
                                        <td class="border border-black px-2 py-1 text-center bg-gray-50">{{ \Carbon\Carbon::parse($escala->data)->format('d/m/Y') }}</td>
                                        <td colspan="4" class="border border-black px-4 py-1 text-center italic text-gray-500">- {{ $escala->atividade->nome }}: Sem Efetivo -</td>
                                    </tr>
                                    @endif
                                    
                                    @if($escala->turno != '' )
                                        <td colspan="16" class="border border-black px-4 py-2 text-center font-bold text-base italic text-red-500 "><b>Turno:</b>{{$escala->turno}}</td>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach

                    @if(!empty($observacoesPadrao))
                    <div class="mt-4 text-[12px] border border-black p-2 bg-gray-50 assinatura-block text-justify">
                        <div class="mt-1 leading-relaxed">
                            {!! $observacoesPadrao !!}
                        </div>
                    </div>
                    @endif

                    @php
                        $meses = [
                            1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
                            5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
                            9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
                        ];

                        $dia = date('d');
                        $mes = $meses[(int)date('m')];
                        $ano = date('Y');
                    @endphp

                    <div class="mt-4 mb-4 text-center text-sm">
                        Divinópolis, <?php echo "$dia de $mes de $ano"; ?>
                    </div>


                    <div class="mt-8 mb-2 text-center assinatura-block">
                        <p class="font-bold uppercase text-sm">JOSÉ ARTHUR FIGUEIRAS DEOLINO – CAP PM</p>
                        <p class="font-bold uppercase text-sm">CMT DA 231ª CIA ET</p>
                    </div>

                </td>
            </tr>
        </tbody>
    </table>                                               
</body>
</html>