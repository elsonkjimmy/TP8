<?php
$path = __DIR__ . '/../resources/views/teacher/dashboard.blade.php';
$lines = file($path);
foreach ($lines as $i => $l) {
    printf("%4d: %s\n", $i+1, rtrim($l, "\r\n"));
}
