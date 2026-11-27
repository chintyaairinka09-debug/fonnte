<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../functions/helpers.php';
require __DIR__ . '/../functions/fonnte.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../register.php');
}

$csrf = (string) ($_POST['csrf_token'] ?? '');
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    redirect('../register.php?error=gagal');
}

$nama = trim((string) ($_POST['nama'] ?? ''));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$nomorInput = trim((string) ($_POST['no_hp'] ?? ''));
$nomor = formatNomorWhatsApp($nomorInput);
$alamat = trim((string) ($_POST['alamat'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($nama === '' || $email === '' || $nomorInput === '' || $alamat === '' || $password === '') {
    redirect('../register.php?error=lengkap');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect('../register.php?error=email');
}
if (strlen($nomor) < 10 || strlen($nomor) > 15 || !preg_match('/^62[0-9]+$/', $nomor)) {
    redirect('../register.php?error=nomor');
}
if (strlen($password) < 8) {
    redirect('../register.php?error=password');
}

try {
    $check = $pdo->prepare('SELECT email, no_hp FROM users WHERE email = :email OR no_hp = :no_hp LIMIT 1');
    $check->execute(['email' => $email, 'no_hp' => $nomor]);
    $existing = $check->fetch();

    if ($existing) {
        if (strcasecmp((string) $existing['email'], $email) === 0) {
            redirect('../register.php?error=duplikat_email');
        }
        redirect('../register.php?error=duplikat_nomor');
    }

    $pdo->beginTransaction();
    $insert = $pdo->prepare(
        'INSERT INTO users (nama, email, no_hp, alamat, password) VALUES (:nama, :email, :no_hp, :alamat, :password)'
    );
    $insert->execute([
        'nama' => $nama,
        'email' => $email,
        'no_hp' => $nomor,
        'alamat' => $alamat,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
    $userId = (int) $pdo->lastInsertId();
    $pdo->commit();

    // Pesan dibuat dari data pengguna yang baru saja berhasil disimpan.
    $pesan = "🎉 REGISTRASI BERHASIL\n\n" .
        "Halo, {$nama}!\n" .
        "Akun Anda berhasil dibuat.\n\n" .
        "Nama: {$nama}\n" .
        "Email: {$email}\n" .
        "WhatsApp: {$nomor}\n" .
        "Alamat: {$alamat}\n\n" .
        "Terima kasih telah melakukan registrasi.";

    $hasilKirim = kirimWhatsApp($nomor, $pesan);
    $log = $pdo->prepare(
        'INSERT INTO log_whatsapp (user_id, no_tujuan, pesan, status, response) VALUES (:user_id, :no_tujuan, :pesan, :status, :response)'
    );
    $log->execute([
        'user_id' => $userId,
        'no_tujuan' => $nomor,
        'pesan' => $pesan,
        'status' => $hasilKirim['status'],
        'response' => $hasilKirim['response'],
    ]);

    $_SESSION['hasil_registrasi'] = [
        'nama' => $nama,
        'email' => $email,
        'no_hp' => $nomor,
        'alamat' => $alamat,
        'status_wa' => $hasilKirim['status'],
        'pesan_status' => $hasilKirim['response'],
    ];
    unset($_SESSION['csrf_token']);
    redirect('../hasil.php');
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Unique index tetap melindungi dari registrasi ganda yang terjadi bersamaan.
    if ($e->getCode() === '23000') {
        redirect('../register.php?error=duplikat_email');
    }
    error_log('Register error: ' . $e->getMessage());
    redirect('../register.php?error=gagal');
}
