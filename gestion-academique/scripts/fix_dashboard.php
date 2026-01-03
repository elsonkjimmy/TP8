<?php
$path = __DIR__ . '/../resources/views/teacher/dashboard.blade.php';
$txt = file_get_contents($path);
$replacements = [
    "/\}\}\s*\xE2\x80\x94\s*\{\{\s*\\n\\s*ucfirst/" => "", // placeholder, will do simpler replacements below
];
// Specific fixes
$txt = preg_replace('/\}\}\s*—\s*\{\{\s*\R\s*ucfirst/', '}} — {{ ucfirst', $txt);
$txt = preg_replace('/repor\R\s*t->/', 'report->', $txt);
$txt = preg_replace('/assignDe\R\s*legate/', 'assignDelegate', $txt);
$txt = preg_replace('/updateSt\R\s*atus/', 'updateStatus', $txt);
$txt = preg_replace('/selec\R\s*ted/', 'selected', $txt);
$txt = preg_replace('/OK<\R\s*\/button>/', 'OK</button>', $txt);
$txt = preg_replace('/cre\R\s*ate/', 'create', $txt); // fix create splits
$txt = preg_replace('/sho\R\s*w/', 'show', $txt);
// Remove any leftover lone CR in the middle of words: replace CRLF with LF, then collapse patterns of letter+LF+letter to letter+letter when suspicious
$txt = str_replace("\r\n", "\n", $txt);
// fix cases where a newline splits a word: e.g., "forma\nt" -> "format"
$txt = preg_replace('/([a-zA-Z])\n([a-zA-Z])/', '$1$2', $txt);
file_put_contents($path, $txt);
echo "fixed\n";
