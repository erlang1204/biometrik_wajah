<?php 
session_start();
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../libs/aes.php';

// user id di dalam session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: http://localhost:84/biometrik_wajah/index.php");
    exit(); // Pastikan untuk mengakhiri eksekusi skrip setelah pengalihan
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psikotes PT.Sinar Metrindo Perkasa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image: url("./asset/bg1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            color: #fff;
        }

        .content {
            background-color: rgba(0, 0, 0, 0.5);
            
            
            margin-top: 2rem;
            text-align: center;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
        }

        h1 {
            color: black;
            padding: 1rem;
        }

        p {
            color: black;
        }

        .kunci {
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<?php
require __DIR__ . '/../../includes/header.php';
?>

<?php
require __DIR__ . '/../../includes/navbar.php';
?>

<body>
<input type="text" value="<?php echo $user_id ?>" hidden>
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-secondary w-100">
    <form action="action-logout.php" method="post" class="w-100">
        <div class="d-flex justify-content-between w-100">
            <a class="navbar-brand ml-5">
                <img src="./asset/logo.png" alt="Logo" height="30" class="d-inline-block align-top mr-2">
                PT. Sinar Metrindo Perkasa
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <button class="nav-link btn btn-link text-white" type="submit" id="logoutButton" style="background:none; border:none;">Log Out</button>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</nav> -->

<div class="content">
    <div class="card" style="width: 33rem; margin: auto;">
        <h1>CFIT</h1>
        <div class="card-body">
            <p class="card-text">
                "Tugas Anda adalah mengisi kotak yang masih kosong sesuai dengan pilihan yang tersedia. Perlu diingat bahwa setiap gambar pada kotak tersebut memiliki pola tertentu. Anda perlu mengetahui pola tersebut untuk menjawab soal."
            </p>
            <p class="kunci">Kunci: 1:C 2:E 3:E</p>
            <div class="d-flex justify-content-center">
                <img class="card-img-top" style="width: 30rem; padding-top: 1rem; padding-bottom: 1rem;" src="../../assets/images/contoh_subtes.jpeg" alt="Contoh Subtes">
            </div>
            <a href="<?= BASE_URL; ?>/modules/subtes/index.php?id=1" class="btn btn-secondary btn-block">Lanjut</a>
        </div>
    </div>
</div>

<!-- Bootstrap Js -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
