<?php

$check_finished_test = $conn->prepare("SELECT * FROM tbl_user WHERE id = ? AND finish_test = 1 LIMIT 1");
$check_finished_test->execute([$_SESSION['user_id']]);
$check_finished_test = $check_finished_test->rowCount();
?>

<div class="d-flex justify-content-center align-items-center w-100" style="max-width: 800px; margin: 0 auto; min-height: 80vh">
    <?php
    $message = "";
    $alert = "success";
    $show_alert = false;

    if (isset($_SESSION['update_profile_success'])) {
        $message = $_SESSION['update_profile_success'];
        $alert = "success";
        $show_alert = true;
        unset($_SESSION['update_profile_success']);
    }
    ?>
    <div class="content">
        <div class="card" style="width: 18rem; display: flex; align-items: center;background: rgba(255,255,255,0.5)">
            <img class="card-img-top" style="width: 18rem; padding-top: 2rem;" src="<?= BASE_URL; ?>/assets/images/psikotes.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title" style="color-text: black; display: flex; justify-content: center; ">CFIT</h5>
                <p class="card-text" style="color: black;">Deretan kotak yang berisi gambar dengan karakteristik serupa. Tugas anda melengkapi kotak sesuai pola.</p>

                <?php
                if ($check_finished_test > 0) {
                ?>
                    <div class="alert alert-success text-center" role="alert">
                        Anda telah selesai mengerjakan Test.
                    </div>
                <?php
                } else {
                ?>
                    <a href="<?= BASE_URL; ?>/modules/subtes/index.php" class="btn btn-secondary" style="display: flex; justify-content: center;">Lanjut</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>