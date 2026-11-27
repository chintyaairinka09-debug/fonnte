<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../functions/helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('login.php');
$configFile = __DIR__ . '/../config/admin.local.php';
if (!is_file($configFile)) redirect('login.php?error=1');
$config = require $configFile;
$expected = (string) ($config['password'] ?? '');
$given = (string) ($_POST['password'] ?? '');
if ($expected !== '' && hash_equals($expected, $given)) {
    session_regenerate_id(true);
    $_SESSION['admin_log_in'] = true;
    redirect('log-whatsapp.php');
}
redirect('login.php?error=1');
