<?php
if (!isset($_GET['username'])) {
    echo "Username tidak ditemukan.";
    exit();
}

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['username']); // sanitasi

$escapedUsername = escapeshellarg($username);
$pythonScriptPath = "C:/xampp/htdocs/psikotes-simetri.my.id/mojokerto-facrec-realtime/register_biometrik_wajah.py";

// Jalankan Python secara background (tidak menunggu sampai selesai)
pclose(popen("start /B python $pythonScriptPath $escapedUsername", "r"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Biometrik Wajah</title>
</head>
<body>
    <h2>Proses pengambilan wajah sedang berjalan...</h2>
    <p>Silakan ikuti instruksi di layar Python yang muncul dan arahkan wajah sesuai arah yang diminta.</p>
    <p>Jika sudah selesai, Anda bisa kembali ke <a href="../../index.php">Halaman Utama</a>.</p>
</body>
</html>
