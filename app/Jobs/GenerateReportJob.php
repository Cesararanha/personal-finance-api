<?php

namespace App\Jobs;

use App\Mail\ReportReadyMail;
use App\Mappers\TransactionMapper;
use App\Models\ReportRequest;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int $reportRequestId,
    ) {}

    public function handle(): void
    {
        $report = ReportRequest::with('user')->findOrFail($this->reportRequestId);
        $report->update(['status' => 'processing']);

        try {
            $transactions = $this->fetchTransactions($report);
            $filePath = $report->type === 'pdf'
                ? $this->generatePdf($report, $transactions)
                : $this->generateCsv($report, $transactions);

            $report->update(['status' => 'done', 'file_path' => $filePath]);

            Mail::to($report->user->email)->send(
                new ReportReadyMail($report->id, $report->type, $report->user->name)
            );
        } catch (\Exception $e) {
            $report->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function fetchTransactions(ReportRequest $report): array
    {
        $filters = $report->filters ?? [];
        $query = Transaction::with('category')->where('user_id', $report->user_id);

        if (! empty($filters['month'])) {
            $start = Carbon::createFromFormat('Y-m', $filters['month'])->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $query->whereBetween('date', [$start, $end]);
        } elseif (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', (int) $filters['category_id']);
        }

        return $query->orderByDesc('date')->get()
            ->map(fn ($t) => TransactionMapper::toArray(TransactionMapper::toDTO($t)))
            ->toArray();
    }

    private function generatePdf(ReportRequest $report, array $transactions): string
    {
        $period = $this->resolvePeriod($report->filters);
        $totalAmount = array_sum(array_column($transactions, 'amount'));

        $pdf = Pdf::loadView('reports.transactions', [
            'transactions' => $transactions,
            'userName' => $report->user->name,
            'period' => $period,
            'totalAmount' => $totalAmount,
        ]);

        $path = "reports/{$report->user_id}/{$report->id}.pdf";
        Storage::put($path, $pdf->output());

        return $path;
    }

    private function generateCsv(ReportRequest $report, array $transactions): string
    {
        $rows = ["Data,Descrição,Categoria,Valor"];
        foreach ($transactions as $t) {
            $rows[] = implode(',', [
                $t['date'],
                '"'.str_replace('"', '""', $t['description'] ?? '')  .'"',
                '"'.str_replace('"', '""', $t['category_name'] ?? '')  .'"',
                number_format($t['amount'], 2, '.', ''),
            ]);
        }

        $path = "reports/{$report->user_id}/{$report->id}.csv";
        Storage::put($path, implode("\n", $rows));

        return $path;
    }

    private function resolvePeriod(?array $filters): ?string
    {
        if (empty($filters)) {
            return null;
        }
        if (! empty($filters['month'])) {
            return Carbon::createFromFormat('Y-m', $filters['month'])->translatedFormat('F \d\e Y');
        }
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            return Carbon::parse($filters['start_date'])->format('d/m/Y')
                .' até '
                .Carbon::parse($filters['end_date'])->format('d/m/Y');
        }

        return null;
    }
}
