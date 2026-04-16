<?php

namespace App\Jobs;

use App\Mappers\RecurringTransactionMapper;
use App\Mappers\TransactionMapper;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessRecurringTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function handle(): void
    {
        $due = RecurringTransaction::with(['category', 'user'])
            ->where('is_active', true)
            ->where('next_due_date', '<=', Carbon::today())
            ->get();

        foreach ($due as $recurring) {
            try {
                $transaction = Transaction::create([
                    'user_id' => $recurring->user_id,
                    'category_id' => $recurring->category_id,
                    'description' => $recurring->description,
                    'amount' => $recurring->amount,
                    'type' => 'expense',
                    'date' => Carbon::today(),
                ]);

                $transaction->load('category');
                $transactionData = TransactionMapper::toArray(TransactionMapper::toDTO($transaction));

                SendTransactionNotificationJob::dispatch(
                    $transactionData,
                    $recurring->user->email,
                    $recurring->user->name,
                )->onConnection('rabbitmq')->onQueue('notifications');

                $recurring->update([
                    'next_due_date' => $this->nextDate($recurring->next_due_date, $recurring->frequency),
                ]);
            } catch (\Exception $e) {
                Log::error('ProcessRecurringTransactionsJob: '.$e->getMessage());
            }
        }
    }

    private function nextDate(Carbon $current, string $frequency): Carbon
    {
        return match ($frequency) {
            'daily' => $current->addDay(),
            'weekly' => $current->addWeek(),
            'monthly' => $current->addMonth(),
        };
    }
}
