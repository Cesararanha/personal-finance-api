<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Transações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #1a1a2e;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #1a1a2e;
        }
        .header p {
            margin: 4px 0 0;
            color: #777;
            font-size: 11px;
        }
        .summary {
            background: #f4f4f4;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            gap: 32px;
        }
        .summary-item span {
            display: block;
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
        }
        .summary-item strong {
            font-size: 14px;
            color: #1a1a2e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background-color: #1a1a2e;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
        }
        .amount {
            color: #e74c3c;
            font-weight: 600;
        }
        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #aaa;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Personal Finance — Relatório de Transações</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; Usuário: {{ $userName }}</p>
        @if($period)
            <p>Período: {{ $period }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-item">
            <span>Total de transações</span>
            <strong>{{ count($transactions) }}</strong>
        </div>
        <div class="summary-item">
            <span>Valor total</span>
            <strong>R$ {{ number_format($totalAmount, 2, ',', '.') }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                <td>{{ $transaction['description'] ?? '—' }}</td>
                <td>{{ $transaction['category_name'] ?? '—' }}</td>
                <td class="amount">R$ {{ number_format($transaction['amount'], 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; color:#aaa; padding: 20px;">
                    Nenhuma transação encontrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Personal Finance API &mdash; Relatório gerado automaticamente</div>
</body>
</html>
