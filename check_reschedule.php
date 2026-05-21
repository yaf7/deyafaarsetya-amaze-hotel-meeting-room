<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reservations = \App\Models\Reservation::latest()->take(10)->get();

foreach ($reservations as $r) {
    $eventDate = \Carbon\Carbon::parse($r->date);
    $daysUntil = now()->diffInDays($eventDate, false);
    $canReschedule = (
        $r->status === 'sukses' 
        && $r->reschedule_count == 0 
        && ($r->reschedule_status === null || $r->reschedule_status === 'rejected') 
        && $daysUntil >= 3
    );
    
    echo "ID:{$r->id} | status:{$r->status} | date:{$r->date->format('Y-m-d')} | days_until:{$daysUntil} | resched_count:{$r->reschedule_count} | resched_status:" . ($r->reschedule_status ?? 'null') . " | CAN_RESCHEDULE:" . ($canReschedule ? 'YES' : 'NO') . "\n";
}
