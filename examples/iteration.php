<?php
require_once __DIR__ . '/../src/Text/Ngram.php';

use Text\Ngram;

$ngram = new Ngram('こんにちは世界！', 2, 'UTF-8');
foreach ($ngram as $key => $value) {
    echo "{$key} : {$value}" . PHP_EOL;
}
