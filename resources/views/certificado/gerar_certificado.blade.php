<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Certificado</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica; display: flex; justify-content: center; padding-top: 50px; background: #f5f5f7; }
        form { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input { padding: 10px; width: 250px; border: 1px solid #d2d2d7; border-radius: 8px; margin-right: 10px; }
        button { padding: 10px 20px; background: #0071e3; color: white; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <form action="/gerar_pdf.php" method="POST">
        <input type="text" name="nome" placeholder="Nome do Aluno" required>
        <input type="text" name="data" placeholder="Data Divinópolis-MG, 24 de Dezembro de 2025" required>
        <button type="submit">Gerar PDF</button>
        
    </form>
</body>
</html>