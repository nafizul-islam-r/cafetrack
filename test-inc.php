<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Grab a board game that has >0 available_units
$game = App\Models\BoardGame::where('available_units', '>', 0)->first();
if (!$game) {
    echo "No games available to test.\n";
    exit;
}
echo "Before decrement: " . $game->available_units . " (Type: " . gettype($game->available_units) . ")\n";

try {
    $game->decrement('available_units');
    
    // Refresh from db
    $game->refresh();
    echo "After decrement: " . $game->available_units . " (Type: " . gettype($game->available_units) . ")\n";
    
    // Increment back
    $game->increment('available_units');
    $game->refresh();
    echo "After increment: " . $game->available_units . "\n";
    
    echo "SUCCESS!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
