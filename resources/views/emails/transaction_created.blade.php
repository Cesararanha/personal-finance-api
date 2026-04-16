<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova transação registrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #1a1a2e;
            color: #ffffff;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .body {
            padding: 32px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .card-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
        }
        .card-row:last-child {
            border-bottom: none;
        }
        .card-row .label {
            color: #777777;
        }
        .card-row .value {
            font-weight: 600;
            color: #1a1a2e;
        }
        .amount {
            font-size: 28px;
            font-weight: 700;
            color: #e74c3c;
            margin: 16px 0 4px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 16px 32px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Personal Finance</h1>
        </div>
        <div class="body">
            <p class="greeting">Olá, <strong>{{ $userName }}</strong>!</p>
            <p>Uma nova transação foi registrada na sua conta:</p>

            <div class="card">
                <p class="amount">- R$ {{ number_format($transactionData['amount'], 2, ',', '.') }}</p>

                <div class="card-row">
                    <span class="label">Categoria</span>
                    <span class="value">{{ $transactionData['category_name'] ?? '—' }}</span>
                </div>
                <div class="card-row">
                    <span class="label">Data</span>
                    <span class="value">{{ \Carbon\Carbon::parse($transactionData['date'])->format('d/m/Y') }}</span>
                </div>
                @if (!empty($transactionData['description']))
                <div class="card-row">
                    <span class="label">Descrição</span>
                    <span class="value">{{ $transactionData['description'] }}</span>
                </div>
                @endif
            </div>

            <p style="font-size: 13px; color: #777777;">
                Se você não reconhece essa transação, acesse sua conta e verifique seu histórico.
            </p>
        </div>
        <div class="footer">
            <p>Este é um e-mail automático. Não responda esta mensagem.</p>
        </div>
    </div>
</body>
</html>
