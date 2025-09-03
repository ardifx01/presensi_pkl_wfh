<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Helpers\KelasNormalizer;

echo "🧪 Testing KelasNormalizer Helper\n";
echo "================================\n\n";

// Test normalisasi kelas
$testKelas = [
    '12rpl1',
    '12 AKUNTANSI 4',
    '12MANAJEMEN LOGISTIK3',
    '12 perhotelan 2',
    '12TKJ1',
    '12 BISNIS DIGITAL 3'
];

echo "📋 Test Normalisasi Kelas:\n";
foreach ($testKelas as $kelas) {
    $normalized = KelasNormalizer::normalize($kelas);
    echo "'{$kelas}' → '{$normalized}'\n";
}

echo "\n📋 Test Normalisasi Sesi:\n";
$testSesi = [
    'pagi',
    'siang',
    'malam',
    'sore',
    '10.00',
    '14.00',
    'morning'
];

foreach ($testSesi as $sesi) {
    $normalized = KelasNormalizer::normalizeSesi($sesi);
    echo "'{$sesi}' → '{$normalized}'\n";
}

echo "\n✅ Test completed!\n";
