# Project API WhatsApp - Fonnte

Project latihan PHP Native + MySQL untuk membuat form registrasi dan mengirim notifikasi WhatsApp otomatis menggunakan Fonnte setelah data berhasil disimpan.

## Teknologi
- PHP 8+
- MySQL / MariaDB
- PDO
- cURL
- Fonnte WhatsApp API
- HTML dan CSS responsif

## Fitur
- Form registrasi: nama, email, nomor WhatsApp, alamat, dan password.
- Validasi input dan pemeriksaan email/nomor ganda.
- Password disimpan menggunakan `password_hash()`.
- Normalisasi nomor lokal `08...` menjadi format `628...`.
- Pengiriman WhatsApp setelah INSERT database berhasil.
- Status pengiriman `success`, `failed`, atau `pending` disimpan ke tabel `log_whatsapp`.
- Halaman admin untuk melihat laporan notifikasi.

## Struktur folder
```text
project-api-fonnte/
├── admin/
│   ├── login.php
│   ├── proses-login.php
│   ├── log-whatsapp.php
│   └── logout.php
├── assets/style.css
├── config/
│   ├── database.php
│   ├── fonnte.php
│   ├── fonnte.local.example.php
│   └── admin.local.example.php
├── functions/
│   ├── helpers.php
│   └── fonnte.php
├── proses/register.php
├── database.sql
├── hasil.php
├── index.php
└── register.php
```

## Cara menjalankan di XAMPP
1. Ekstrak folder `project-api-fonnte` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `database.sql`.
5. Pastikan pengaturan database pada `config/database.php` sesuai XAMPP. Default XAMPP biasanya user `root` tanpa password.
6. Salin `config/fonnte.local.example.php` menjadi `config/fonnte.local.php`.
7. Isi token Fonnte pada file lokal tersebut. Jangan unggah file ini ke GitHub.
8. Salin `config/admin.local.example.php` menjadi `config/admin.local.php`. Ganti password admin demo `admin123`.
9. Pastikan ekstensi PHP cURL aktif dan perangkat WhatsApp sudah tersambung ke Fonnte.
10. Buka `http://localhost/project-api-fonnte/` dan lakukan registrasi dengan nomor WhatsApp milik sendiri untuk pengujian.

## Alur sistem
`Form Register -> Validasi -> INSERT users -> API Fonnte -> INSERT log_whatsapp -> Hasil Registrasi`

Pesan WhatsApp tidak dikirim apabila data gagal disimpan. Jika token belum dikonfigurasi, registrasi tetap disimpan dan status pesan dicatat sebagai `pending`.

## API Fonnte
Aplikasi menggunakan endpoint `https://api.fonnte.com/send` dengan HTTP POST. Token dikirim pada header `Authorization` dari server PHP, sedangkan nomor tujuan dan isi pesan dikirim sebagai parameter `target` dan `message`. Periksa dokumentasi resmi Fonnte untuk perubahan API: https://docs.fonnte.com/api-send-message/.

## Halaman log admin
- URL: `http://localhost/project-api-fonnte/admin/login.php`
- Password awal dari contoh konfigurasi: `admin123` (wajib diganti untuk penggunaan sendiri).

## Dokumentasi tugas
Buat Google Docs dan masukkan:
1. Screenshot form registrasi.
2. Screenshot record user pada tabel `users`.
3. Screenshot pesan WhatsApp yang benar-benar diterima.
4. Screenshot source code `functions/fonnte.php` (function dan HTTP Request) serta `proses/register.php` (proses register dan pemanggilan WhatsApp).
5. Screenshot skenario pengujian, termasuk email/nomor duplikat dan respons API.
6. Link repository GitHub.

## Keamanan
- `config/fonnte.local.php` dan `config/admin.local.php` ada di `.gitignore`.
- Jangan commit atau mengirim token Fonnte, password, atau data pribadi ke repository publik.
- Screenshot dashboard Fonnte harus menyamarkan token.

## Catatan status
Status `success` berarti API Fonnte merespons sukses/permintaan diterima. Pastikan pesan benar-benar masuk di WhatsApp penerima sebagai bukti pengujian. Status `pending` berarti token belum dipasang; `failed` berarti API atau koneksi memberi respons gagal.
