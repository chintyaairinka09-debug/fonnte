<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions/helpers.php';

$errorMessages = [
    'lengkap' => 'Semua kolom wajib diisi.',
    'email' => 'Format email tidak valid.',
    'nomor' => 'Nomor WhatsApp tidak valid. Masukkan nomor Indonesia yang benar.',
    'password' => 'Password minimal 8 karakter.',
    'duplikat_email' => 'Email sudah terdaftar. Gunakan email lain.',
    'duplikat_nomor' => 'Nomor WhatsApp sudah terdaftar. Gunakan nomor lain.',
    'gagal' => 'Registrasi belum berhasil. Periksa koneksi database lalu coba lagi.',
];
$error = $errorMessages[$_GET['error'] ?? ''] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Registrasi | Project API WhatsApp</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar"><div class="nav-inner"><div class="brand">API <span>WHATSAPP</span></div><a class="nav-link" href="admin/login.php">Admin Log</a></div></nav>
<main class="container">
    <div class="layout">
        <section class="intro">
            <div class="eyebrow">Project Praktik API</div>
            <h1>Registrasi Akun dengan Notifikasi WhatsApp</h1>
            <p>Daftarkan akun dengan mengisi data yang benar. Setelah data berhasil disimpan, sistem akan mencoba mengirim notifikasi melalui WhatsApp Gateway Fonnte.</p>
            <ul class="feature-list">
                <li>Validasi nama, email, nomor, dan password</li>
                <li>Penyimpanan data ke MySQL</li>
                <li>Nomor WhatsApp otomatis dinormalisasi</li>
                <li>Log status pengiriman pesan</li>
            </ul>
        </section>
        <section class="panel">
            <h2>Buat Akun Baru</h2>
            <p class="sub">Lengkapi formulir di bawah ini untuk melakukan registrasi.</p>
            <?php if ($error !== ''): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
            <form action="proses/register.php" method="post" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ??= bin2hex(random_bytes(32))) ?>">
                <div class="form-grid">
                    <div class="field full"><label for="nama">Nama lengkap</label><input id="nama" name="nama" type="text" maxlength="100" placeholder="Contoh: Budi Santoso" required></div>
                    <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="100" placeholder="nama@email.com" required></div>
                    <div class="field"><label for="no_hp">Nomor WhatsApp</label><input id="no_hp" name="no_hp" type="tel" maxlength="20" placeholder="081234567890" required><span class="hint">Boleh memakai awalan 08 atau 62.</span></div>
                    <div class="field full"><label for="alamat">Alamat</label><input id="alamat" name="alamat" type="text" maxlength="255" placeholder="Kota atau alamat tempat tinggal" required></div>
                    <div class="field full"><label for="password">Password</label><input id="password" name="password" type="password" minlength="8" placeholder="Minimal 8 karakter" required><span class="hint">Password disimpan menggunakan password_hash().</span></div>
                </div>
                <button class="btn full" type="submit">Register Sekarang</button>
            </form>
        </section>
    </div>
    <p class="footer">Project API WhatsApp · PHP · MySQL · Fonnte</p>
</main>
</body>
</html>
