<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['admin_log_in'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../functions/helpers.php';
$stmt = $pdo->query('SELECT l.id, u.nama, l.no_tujuan, l.pesan, l.status, l.response, l.created_at FROM log_whatsapp l LEFT JOIN users u ON u.id = l.user_id ORDER BY l.id DESC LIMIT 100');
$logs = $stmt->fetchAll();
$labels = ['success' => 'Terkirim / API menerima', 'failed' => 'Gagal', 'pending' => 'Pending'];
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Log WhatsApp</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<nav class="navbar"><div class="nav-inner"><div class="brand">API <span>WHATSAPP</span></div><a class="nav-link" href="logout.php">Logout</a></div></nav>
<main class="container"><section class="panel"><div class="topline"><div><h2>Laporan Notifikasi WhatsApp</h2><p class="sub" style="margin:6px 0 0">Menampilkan maksimal 100 catatan pengiriman terbaru.</p></div><a class="btn" href="../register.php">Form Register</a></div>
<div class="table-wrap"><table class="data"><thead><tr><th>Nama</th><th>Nomor</th><th>Waktu</th><th>Status</th><th>Pesan</th></tr></thead><tbody>
<?php if (!$logs): ?><tr><td colspan="5">Belum ada log pengiriman.</td></tr><?php endif; ?>
<?php foreach ($logs as $log): ?><tr><td><?= e($log['nama'] ?? '-') ?></td><td><?= e($log['no_tujuan']) ?></td><td><?= e($log['created_at']) ?></td><td><span class="status <?= e($log['status']) ?>"><?= e($labels[$log['status']] ?? $log['status']) ?></span></td><td><?= e(mb_strimwidth($log['pesan'], 0, 110, '…')) ?></td></tr><?php endforeach; ?>
</tbody></table></div></section></main></body></html>
