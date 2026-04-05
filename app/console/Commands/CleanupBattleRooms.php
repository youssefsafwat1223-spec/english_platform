<?php

namespace App\Console\Commands;

use App\Models\BattleRoom;
use Illuminate\Console\Command;

class CleanupBattleRooms extends Command
{
    protected $signature = 'battle:cleanup-rooms';
    protected $description = 'Cleanup old battle rooms (finished or stale).';

    public function handle(): int
    {
        $deleted = BattleRoom::where('status', 'finished')
            ->orWhere(function ($query) {
                $query->where('status', 'waiting')
                    ->where('created_at', '<', now()->subHours(6));
            })
            ->delete();

        $this->info("Deleted {$deleted} battle rooms.");

        return self::SUCCESS;
    }
}

