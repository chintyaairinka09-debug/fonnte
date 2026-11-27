<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatNomorWhatsApp(string $nomor): string
{
    $nomor = preg_replace('/\D+/', '', $nomor) ?? '';

    if (str_starts_with($nomor, '0')) {
        $nomor = '62' . substr($nomor, 1);
    } elseif (str_starts_with($nomor, '8')) {
        $nomor = '62' . $nomor;
    }

    return $nomor;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}
