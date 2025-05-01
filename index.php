<?php
session_start();
require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == "admin") {
        header("Location: " . BASE_URL . "/modules/admin/index.php");
        exit();
    }
    header("Location: " . BASE_URL . "/modules/user/index.php");
    exit();
}

?>

<?php
require __DIR__ . '/includes/header.php';
?>
<main class="d-flex justify-content-center align-items-center w-100" style="margin: 0 auto; min-height: 100vh">

    <!-- Login Area -->

    <div class="login-container">
        <?php
            $message = "";
            $alert = "success";
            $show_alert = false;
            if (isset($_SESSION['login_error'])) {
                $message = $_SESSION['login_error'];
                $alert = "danger";
                $show_alert = true;
                unset($_SESSION['login_error']);
            } else if (isset($_SESSION['register_user_success'])) {
                $message = $_SESSION['register_user_success'];
                $alert = "success";
                $show_alert = true;
                unset($_SESSION['register_user_success']);
            } else if (isset($_SESSION['register_user_exists'])) {
                $message = $_SESSION['register_user_exists'];
                $alert = "danger";
                $show_alert = true;
                unset($_SESSION['register_user_exists']);
            } else if (isset($_SESSION['register_errors'])) {
                $message = $_SESSION['register_errors'];
                $alert = "danger";
                $show_alert = true;
                unset($_SESSION['register_errors']);
            }
        ?>
        <div class="login-form" id="loginForm" style="display: flex; flex-direction: column; align-items: center;">
            <?php if ($show_alert) { ?>
                <div class="alert alert-<?= $alert; ?> mb-3" role="alert">
                    <?= $message; ?>
                </div>
            <?php } ?>
            <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Logo" height="100">
            <h2 class="text-center">Log In Your Account</h2>
            <p class="text-center">Welcome! Please Enter Your Details.</p>
            <form action="<?= BASE_URL; ?>/modules/auth/login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <input hidden type="text" name="status"></input>
                </div>
                <button type="submit" class="btn btn-secondary login-btn form-control">Login</button>
                <br />
                <p id="register">No Account?<span style="color:#ffb300; text-decoration:none;" class="switch-form-link-register" onclick="showRegistrationForm()"> Register Here.</span></p>
                <p>Open exam results <a href="<?= BASE_URL; ?>/decrypt-form.php" style="color:#ffb300;text-decoration:none;">Here.</a></p>

            </form>
        </div>

    </div>

    <!-- Registration Area -->
    <div class="registration-form" id="registrationForm">
        <h2 class="text-center">Registration Form</h2>
        <p class="text-center">Fill in you personal details.</p>
        <form action="<?= BASE_URL; ?>/modules/auth/register.php" method="POST" onsubmit="return handleRegistration(event)">
            <div class="form-group registration row">
                <div class="col-12">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <div class="form-group registration row">
                <div class="col-5">
                    <label for="contactNumber">Contact Number:</label>
                    <input type="number" class="form-control" id="contactNumber" name="contact_number" maxlength="11">
                </div>
                <div class="col-7">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
            </div>
            <div class="form-group registration">
                <label for="registerUsername">Username:</label>
                <input type="text" class="form-control" id="registerUsername" name="username">
            </div>
            <div class="form-group registration">
                <label for="registerPassword">Password:</label>
                <input type="password" class="form-control" id="registerPassword" name="password">
            </div>
            <input hidden type="text" class="form-control" name="key" id="" placeholder="Key" value="tes"><br>

            <p>Already have an account? Login <span style="color:black;" class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
            <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
        </form>
        <!-- <video id="video" width="320" height="240" autoplay ></video> -->
<!-- <script>
    // Akses webcam
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            document.getElementById('video').srcObject = stream;
        }).catch(err => {
            console.error("Tidak bisa akses kamera:", err);
        });

    async function handleRegistration(event) {
        event.preventDefault(); // Hentikan form default submit dulu

        const form = document.getElementById('registrationForm');
        const video = document.getElementById('video');
        const canvas = document.createElement('canvas');
        canvas.width = 224;
        canvas.height = 224;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Konversi gambar ke blob
        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
        const formData = new FormData();

        // Ambil data dari input form
        const name = document.getElementById('name').value;
        formData.append('nama', name);
        formData.append('gambar', blob);

        // Kirim ke Flask server
        const response = await fetch('http://localhost:5000/upload-wajah', {
            method: 'POST',
            body: formData
        });

        const result = await response.text();
        if (response.ok) {
            alert("Wajah berhasil direkam: " + result);
            form.submit(); // Lanjutkan submit ke PHP setelah sukses
        } else {
            alert("Gagal deteksi wajah: " + result);
        }
    }
</script> -->

    </div>

    </div>

    <script type="text/javascript">
        const loginForm = document.getElementById('loginForm');
        const registrationForm = document.getElementById('registrationForm');

        registrationForm.style.display = "none";


        function showRegistrationForm() {
            registrationForm.style.display = "";
            loginForm.style.display = "none";
        }

        function showLoginForm() {
            registrationForm.style.display = "none";
            loginForm.style.display = "flex";
        }

        function sendVerificationCode() {
            const registrationElements = document.querySelectorAll('.registration');

            registrationElements.forEach(element => {
                element.style.display = 'none';
            });

            const verification = document.querySelector('.verification');
            if (verification) {
                verification.style.display = 'none';
            }
        }
    </script>

    <?php
    require __DIR__ . '/includes/footer.php';
    ?>