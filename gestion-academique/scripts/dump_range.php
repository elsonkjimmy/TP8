<?php
$path = __DIR__ . '/../resources/views/teacher/dashboard.blade.php';
$lines = file($path);
$start = 130; $end = 140; // 1-based in human; 0-based indices will be 129..139
for ($i = $start-1; $i <= $end-1; $i++) {
    $ln = $lines[$i];
    printf("%4d: %s", $i+1, rtrim($ln, "\r\n")."\n");
}
