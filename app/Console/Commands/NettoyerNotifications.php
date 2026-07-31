<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NettoyerNotifications extends Command
{
    protected $signature   = 'notifier:nettoyer';
    protected $description = 'Supprime les notifications lues depuis plus de 7 jours.';

    public function handle(): void
    {
        $supprimees = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays(7))
            ->delete();

        $this->info("Notifications supprimées : {$supprimees}");
    }
}
