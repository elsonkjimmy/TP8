<?php
$path = __DIR__ . '/../resources/views/teacher/dashboard.blade.php';
$lines = file($path);
$idx = 40; // line 41 (0-based)
$line = $lines[$idx];
foreach (preg_split('//u', $line, -1, PREG_SPLIT_NO_EMPTY) as $i => $ch) {
    $ord = ord($ch);
    $disp = $ch === "\r" ? '<CR>' : ($ch === "\n" ? '<LF>' : $ch);
    printf("%03d: 0x%02X %s\n", $i, $ord, $disp);
}
