<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions/helpers.php';
$hasil = $_SESSION['hasil_registrasi'] ?? null;
if (!is_array($hasil)) {
    redirect('register.php');
}
unset($_SESSION['hasil_registrasi']);
$status = (string) ($hasil['status_wa'] ?? 'pending');
$statusLabel = ['success' => 'Respons API berhasil', 'failed' => 'Pengiriman gagal', 'pending' => 'Belum dikirim / menunggu konfigurasi'][$status] ?? 'Status tidak diketahui';
$statusClass = in_array($status, ['success', 'failed', 'pending'], true) ? $status : 'pending';
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Hasil Registrasi</title><link rel="stylesheet" href="assets/style.css"></head>
<body><nav class="navbar"><div class="nav-inner"><div class="brand">API <span>WHATSAPP</span></div><a class="nav-link" href="register.php">Form Registrasi</a></div></nav>
<main class="container"><section class="card">
    <div class="topline"><div><div class="eyebrow" style="color:#1f8061">Registrasi selesai</div><h1 style="margin:8px 0 0">Terima kasih, <?= e($hasil['nama']) ?>!</h1></div><span class="status <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></div>
    <p class="sub">Data akun berhasil disimpan ke database. Berikut ringkasan data registrasi.</p>
    <table class="data"><tr><th>Nama</th><td><?= e($hasil['nama']) ?></td></tr><tr><th>Email</th><td><?= e($hasil['email']) ?></td></tr><tr><th>Nomor WhatsApp</th><td><?= e($hasil['no_hp']) ?></td></tr><tr><th>Alamat</th><td><?= e($hasil['alamat']) ?></td></tr></table>
    <?php if ($status === 'success'): ?><div class="alert success">Fonnte memberikan respons berhasil. Periksa WhatsApp pada nomor tujuan untuk memastikan pesan diterima.</div>
    <?php elseif ($status === 'failed'): ?><div class="alert error">Registrasi tetap berhasil, tetapi permintaan WhatsApp gagal. Periksa koneksi, konfigurasi token, dan status perangkat Fonnte.</div>
    <?php else: ?><div class="alert info">Registrasi berhasil disimpan. Pesan belum dikirim karena token Fonnte belum diatur.</div><?php endif; ?>
    <a class="btn" href="register.php">Kembali ke Form</a>
</section><p class="footer">Status WhatsApp juga dicatat pada tabel log_whatsapp.</p></main></body></html>
