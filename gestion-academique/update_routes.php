<?php

$filePath = 'd:/TP8/gestion-academique/routes/web.php';

$content = file_get_contents($filePath);

// Find and insert the new routes after the seances resource definition
$pattern = "Route::resource\('seances', AdminSeanceController::class\);";
$replacement = "Route::resource('seances', AdminSeanceController::class);
    
    // Seance generation from templates
    Route::get('seances/generate/form', [\App\Http\Controllers\Admin\SeanceGeneratorController::class, 'showForm'])->name('seances.generate.form');
    Route::post('seances/generate', [\App\Http\Controllers\Admin\SeanceGeneratorController::class, 'generate'])->name('seances.generate.store');";

$newContent = str_replace($pattern, $replacement, $content);

file_put_contents($filePath, $newContent);

echo "Routes file updated successfully.\n";
?>
