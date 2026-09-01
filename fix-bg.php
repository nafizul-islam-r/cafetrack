<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$games = App\Models\BoardGame::all();
foreach ($games as $game) {
    echo "Before DB update: " . gettype($game->getRawOriginal('available_units')) . "\n";
    
    // Use raw query to ensure update
    \Illuminate\Support\Facades\DB::table('board_games')->where('_id', $game->_id)->update([
        'total_units' => (int) $game->total_units,
        'available_units' => (int) $game->available_units,
    ]);
}
echo "Database strictly updated to integers.\n";
