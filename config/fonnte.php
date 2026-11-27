<?php
declare(strict_types=1);

/**
 * Token Fonnte dibaca dari environment variable atau file lokal yang tidak diunggah ke GitHub.
 * Buat config/fonnte.local.php berdasarkan fonnte.local.example.php.
 */
$token = getenv('FONNTE_TOKEN') ?: '';
$localConfig = __DIR__ . '/fonnte.local.php';

if (is_file($localConfig)) {
    $local = require $localConfig;
    if (is_array($local) && !empty($local['token'])) {
        $token = (string) $local['token'];
    }
}

return [
    'endpoint' => 'https://api.fonnte.com/send',
    'token' => trim($token),
    'timeout' => 30,
];
