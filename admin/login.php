<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../functions/helpers.php';
if (!empty($_SESSION['admin_log_in'])) { redirect('log-whatsapp.php'); }
$error = isset($_GET['error']);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<nav class="navbar"><div class="nav-inner"><div class="brand">API <span>WHATSAPP</span></div><a class="nav-link" href="../register.php">Kembali ke Register</a></div></nav>
<main class="container"><section class="card"><h2>Login Administrator</h2><p class="sub">Masuk untuk melihat laporan pengiriman WhatsApp.</p>
<?php if ($error): ?><div class="alert error">Password admin salah atau konfigurasi admin belum dibuat.</div><?php endif; ?>
<form action="proses-login.php" method="post"><div class="field"><label for="password">Password admin</label><input id="password" name="password" type="password" required></div><button class="btn full" type="submit">Masuk</button></form></section></main></body></html>
