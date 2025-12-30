<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_certificado = $_POST['data']; // Recebe a data do formulário
    
    // Lógica de redimensionamento do nome (mantida)
    $comprimento = strlen($nome);
    $fontSizeNome = ($comprimento <= 20) ? "40pt" : (($comprimento <= 35) ? "25pt" : "18pt");

    /*$options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);*/

    // Conversão da imagem para Base64
    $imagemPath = 'modelo.png';
    $base64 = '';
    if (file_exists($imagemPath)) {
        $imgData = file_get_contents($imagemPath);
        $base64 = 'data:image/' . pathinfo($imagemPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imgData);
    }

    $html = '
    <html>
    <head>
        <style>
            @page { margin: 0; }
            body { margin: 0; padding: 0; font-family: "Arial", sans-serif; }
            .container { position: relative; width: 297mm; height: 210mm; }
            .fundo { position: absolute; width: 100%; height: 100%; z-index: -1; }
            
            /* Estilo do Nome */
            .nome-aluno {
                position: absolute;
                top: 85mm; 
                left: 55mm;
                width: 100%;
                font-size: ' . $fontSizeNome . ';
                font-weight: bold;
                text-transform: uppercase;
            }

            /* Estilo da Data (Solicitado: Arial, Tamanho 7) */
            .campo-data {
                position: absolute;
                top: 55mm; /* AJUSTE AQUI para descer ou subir a data */
                right: 40mm;
                width: 100%;
                text-align: right; /* Se a data for no canto, mude para right e use padding-right */
                font-family: Arial, sans-serif;
                font-size: 10pt;
               /* font-weight: bold; */
                color: #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="' . $base64 . '" class="fundo">
            <div class="nome-aluno">' . htmlspecialchars($nome) . '</div>
            <div class="campo-data">' . htmlspecialchars($data_certificado) . '</div>
        </div>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("certificado.pdf", ["Attachment" => false]);
}