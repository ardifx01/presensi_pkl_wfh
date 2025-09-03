<?php

require_once 'vendor/autoload.php';
require_once 'app/Helpers/KelasNormalizer.php';

use App\Helpers\KelasNormalizer;

echo "Testing KelasNormalizer:\n\n";

// Test cases untuk kelas
$testCases = [
    '12rpl1',
    '12 rpl 1', 
    '12RPL1',
    '12 RPL 1',
    '12AKUNTANSI4',
    '12 AKUNTANSI 4',
    '12 PERHOTELAN 2',
    '12bd3'
];

echo "Kelas Normalization:\n";
foreach ($testCases as $test) {
    $result = KelasNormalizer::normalize($test);
    echo "'{$test}' -> '{$result}'\n";
}

echo "\n\nSesi Normalization:\n";

// Test cases untuk sesi
$sesiCases = [
    'pagi',
    '10.00',
    '09.00',
    'Pagi (09.00-12.00 WIB)',
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
