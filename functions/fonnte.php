<?php
declare(strict_types=1);

/**
 * Mengirim pesan teks melalui Fonnte.
 * Return status "success", "failed", atau "pending".
 * "success" berarti API menerima permintaan; bukti penerimaan di perangkat tujuan
 * tetap perlu diperiksa saat pengujian.
 */
function kirimWhatsApp(string $nomor, string $pesan): array
{
    $config = require __DIR__ . '/../config/fonnte.php';

    if (empty($config['token']) || $config['token'] === 'ISI_TOKEN_FONNTE_DI_SINI') {
        return [
            'status' => 'pending',
            'response' => 'Token Fonnte belum dikonfigurasi. Pesan belum dikirim.',
        ];
    }

    if (!function_exists('curl_init')) {
        return [
            'status' => 'failed',
            'response' => 'Ekstensi PHP cURL belum aktif.',
        ];
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $config['endpoint'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => (int) ($config['timeout'] ?? 30),
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => [
            'target' => $nomor,
            'message' => $pesan,
            // Nomor sudah dinormalisasi menjadi format 62xxxxxxxxxx.
            'countryCode' => '0',
        ],
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $config['token'],
        ],
    ]);

    $responseBody = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($responseBody === false || $curlError !== '') {
        return [
            'status' => 'failed',
            'response' => 'cURL error: ' . $curlError,
        ];
    }

    $decoded = json_decode((string) $responseBody, true);
    $apiSaysSuccess = is_array($decoded) && ($decoded['status'] ?? false) === true;

    return [
        'status' => ($httpCode >= 200 && $httpCode < 300 && $apiSaysSuccess) ? 'success' : 'failed',
        'response' => json_encode([
            'http_code' => $httpCode,
            'body' => $decoded ?? (string) $responseBody,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ];
}
