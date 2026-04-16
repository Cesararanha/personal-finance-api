<?php

namespace App\Jobs;

use App\Mail\TransactionCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTransactionNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public readonly array $transactionData,
        public readonly string $userEmail,
        public readonly string $userName,
    ) {}

    public function handle(): void
    {
        Mail::to($this->userEmail)->send(
            new TransactionCreatedMail($this->transactionData, $this->userName)
        );
    }
}
