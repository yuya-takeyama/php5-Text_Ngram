<?php
require_once __DIR__ . '/../src/Text/Ngram.php';

use \Text\Ngram;

$greeting = 'こんにちは世界！';

// Bigram
echo "Bigram" . PHP_EOL;
$ngram = new Ngram($greeting, 2, 'UTF-8');
var_dump($ngram->toArray());

echo PHP_EOL;

// Trigram
echo "Trigram" . PHP_EOL;
$ngram = new Ngram($greeting, 3, 'UTF-8');
var_dump($ngram->toArray());
