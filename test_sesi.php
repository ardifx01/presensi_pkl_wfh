<?php

require_once 'vendor/autoload.php';
require_once 'app/Helpers/KelasNormalizer.php';

use App\Helpers\KelasNormalizer;

echo "Testing Updated KelasNormalizer:\n\n";

echo "Sesi Normalization:\n";

// Test cases untuk sesi
$sesiCases = [
    'pagi',
    '10.00', 
    '09.00',
    'siang',
    '14.00',
    'malam',
    '16.30',
    'sore'
];

foreach ($sesiCases as $test) {
    $result = KelasNormalizer::normalizeSesi($test);
    echo "'{$test}' -> '{$result}'\n";
}
