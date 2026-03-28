<?php

namespace App\Console\Commands;

use App\Services\BattleRoomService;
use Illuminate\Console\Command;

class CleanupBattleRooms extends Command
{
    protected $signature = 'battle:cleanup-stale-rooms';

    protected $description = 'Close abandoned battle rooms and resolve expired lobbies';

    public function handle(BattleRoomService $battleRoomService): int
    {
        $result = $battleRoomService->cleanupStaleRooms();

        $this->info(sprintf(
            'Battle cleanup complete. Started: %d, Cancelled: %d, Abandoned: %d',
            $result['started'],
            $result['cancelled'],
            $result['abandoned']
        ));

        return self::SUCCESS;
    }
}
