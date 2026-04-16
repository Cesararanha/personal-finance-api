<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório pronto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #1a1a2e; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 32px; color: #333; }
        .badge { display: inline-block; background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background-color: #1a1a2e; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; }
        .footer { background: #f4f4f4; padding: 16px 32px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Personal Finance</h1>
        </div>
        <div class="body">
            <p>Olá, <strong>{{ $userName }}</strong>!</p>
            <span class="badge">✓ Relatório pronto</span>
            <p>Seu relatório <strong>{{ strtoupper($reportType) }}</strong> foi gerado com sucesso e já está disponível para download.</p>
            <p>Clique no botão abaixo para baixar. O link expira em <strong>24 horas</strong>.</p>
            <a href="{{ $downloadUrl }}" class="btn">Baixar relatório {{ strtoupper($reportType) }}</a>
        </div>
        <div class="footer">
            <p>Este é um e-mail automático. Não responda esta mensagem.</p>
        </div>
    </div>
</body>
</html>
