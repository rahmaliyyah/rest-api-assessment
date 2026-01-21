<?php

namespace App\Jobs;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transferId;

    public function __construct($transferId)
    {
        $this->transferId = $transferId;
    }

    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $transfer = Transfer::find($this->transferId);

            if (!$transfer || $transfer->status !== 'PENDING') {
                DB::rollBack();
                return;
            }

            $sender = User::find($transfer->user_id);
            $receiver = User::find($transfer->target_user);

            if (!$sender || !$receiver) {
                $transfer->update(['status' => 'FAILED']);
                DB::commit();
                return;
            }

            if ($sender->balance < $transfer->amount) {
                $transfer->update(['status' => 'FAILED']);
                DB::commit();
                return;
            }

            $sender->update([
                'balance' => $sender->balance - $transfer->amount
            ]);

            $receiver->update([
                'balance' => $receiver->balance + $transfer->amount
            ]);

            $transfer->update(['status' => 'SUCCESS']);

            DB::commit();

            Log::info("Transfer {$this->transferId} processed successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            
            $transfer = Transfer::find($this->transferId);
            if ($transfer) {
                $transfer->update(['status' => 'FAILED']);
            }

            Log::error("Transfer {$this->transferId} failed: " . $e->getMessage());
        }
    }
}