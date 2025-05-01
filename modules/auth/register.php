<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require "../../vendor/phpmailer/phpmailer/src/SMTP.php";
require "../../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../../vendor/phpmailer/phpmailer/src/Exception.php";
require "../../libs/AesBase.php";
include '../../config/app.php';
include '../../config/database.php';
include '../../includes/functions.php';

try {
    $username = sanitizeInput($_POST['username']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $email = sanitizeInput($_POST['email']);
    $name = sanitizeInput($_POST['name']);
    $role = "user";

    $a = $_POST['password'];
    $io = substr(md5($a), 0, 16);
    $aes = new AesBase($io);
    $pass_encrypt = bin2hex($aes->encrypt($a));

    $conn->beginTransaction();

    $stmt = $conn->prepare("SELECT `username` FROM `tbl_user` WHERE `username` = :username");
    $stmt->execute(['username' => $username]);
    $name_exist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($name_exist)) {
        $verification_code = rand(100000, 999999);

        $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`name`, `username`, `contact_number`, `email`, `password`, `verification_code`, `role`) 
                                      VALUES (:name, :username, :contact_number, :email, :password, :verification_code, :role)");

        $insertStmt->bindParam(':name', $name, PDO::PARAM_STR);
        $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $insertStmt->bindParam(':contact_number', $contact_number, PDO::PARAM_INT);
        $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $insertStmt->bindParam(':password', $pass_encrypt, PDO::PARAM_STR);
        $insertStmt->bindParam(':verification_code', $verification_code, PDO::PARAM_INT);
        $insertStmt->bindParam(':role', $role, PDO::PARAM_STR);
        $insertStmt->execute();

        // Kirim email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'erlangbayu7@gmail.com';
        $mail->Password = 'hkee hclw qafm qrvs';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');
        $mail->addAddress($email);
        $mail->addReplyTo('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: <a href="index.php">' . $verification_code . '</a>';

        // Uncomment kalau mau kirim email beneran
        // $mail->send();

        $conn->commit();

        // ✅ Panggil script Python biometrik
        // Jalankan script register_biometrik_wajah.py setelah registrasi berhasil
// $escapedName = escapeshellarg($name);
// exec("python C:/xampp/htdocs/psikotes-simetri.my.id/mojokerto-facrec-realtime/register_biometrik_wajah.py $escapedName");
// $_SESSION['register_user_success'] = "Register berhasil. Silakan lanjutkan proses biometrik.";
$escapedName = urlencode($name); // pastikan aman untuk URL
header("Location: http://localhost:5000/biometrik?nama=$escapedName");
exit;

header("Location: " . BASE_URL . "");
    } else {
        $conn->rollBack();
        $_SESSION['register_user_exists'] = "User sudah terdaftar.";
        header("Location: " . BASE_URL . "");
    }

} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['register_errors'] = $e->getMessage();
    header("Location: " . BASE_URL . "");
}
?>
