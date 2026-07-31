<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rappels événements J-7 et J-1 chaque matin à 8h
Schedule::command('notifier:evenements')->dailyAt('08:00');

// Suppression des notifications lues depuis plus de 7 jours
Schedule::command('notifier:nettoyer')->dailyAt('02:00');
