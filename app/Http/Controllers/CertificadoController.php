<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FPDF; // Importa a classe FPDF

class CertificadoController extends Controller
{
    public function gerar(Request $request)
    {
        $nome = $request->input('nome');
        $data_certificado = $request->input('data');

        // Inicializa o FPDF em modo Paisagem (L), milímetros (mm), Papel A4
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        // 1. Inserir a Imagem de Fundo (deve estar na pasta public/)
        $imagemPath = public_path('modelo.png');
        if (file_exists($imagemPath)) {
            // Desenha a imagem ocupando a página toda (297x210mm)
            $pdf->Image($imagemPath, 0, 0, 297, 210);
        }

        // 2. Configurar a Data (Arial, tamanho 10 - conforme seu código anterior)
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0); // Preto
        // Posicionamento: top 55mm, right 40mm (ajustado para coordenadas FPDF)
        $pdf->SetXY(0, 55); 
        $pdf->Cell(257, 10, utf8_decode($data_certificado), 0, 0, 'R');

        // 3. Configurar o Nome do Aluno
        // Lógica de redimensionamento da fonte
        $comprimento = strlen($nome);
        $fontSize = ($comprimento <= 20) ? 40 : (($comprimento <= 35) ? 25 : 18);
        
        $pdf->SetFont('Arial', 'B', $fontSize);
        // Posicionamento: top 85mm, left 55mm
        $pdf->SetXY(55, 85);
        $pdf->Cell(0, 20, strtoupper(utf8_decode($nome)), 0, 0, 'L');

        // 4. Saída do PDF
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="certificado.pdf"');
    }
}