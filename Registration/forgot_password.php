<?php
session_start();
require '../db.php'; 
require '../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Логирование ошибок в файл
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    error_log('Encryption key not found.');
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    error_log('Invalid key size.');
    die('Invalid key size.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $email = htmlspecialchars($email);

    try {
        // Подготовка запроса для получения всех пользователей
        $query = "SELECT * FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $user_found = false;

        foreach ($users as $row) {
            // Извлечение nonce и зашифрованной электронной почты
            $nonce = substr($row['email'], 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_email = substr($row['email'], SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

            // Расшифровка электронной почты
            $decrypted_email = sodium_crypto_secretbox_open($encrypted_email, $nonce, $key);

            if ($decrypted_email === false) {
                error_log('Decryption failed for user: ' . $row['id']);
                continue;
            }

            if ($decrypted_email === $email) {
                $user_found = true;
                $token = bin2hex(random_bytes(50)); 

                // Обновление пользователя с токеном сброса пароля и временем истечения
                $query = "UPDATE users SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':email', $row['email']);
                $stmt->execute();

                // Настройка PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.mail.ru';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sportlife03@mail.ru';
                    $mail->Password = 'mtwDuJLs9CMc6a0iNSur';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;
                    $mail->CharSet = 'UTF-8';

                    // Получатель и отправитель
                    $mail->setFrom('sportlife03@mail.ru', 'SportLife');
                    $mail->addAddress($email);

                    // Содержание письма
                    $mail->isHTML(true);
                    $mail->Subject = 'Сброс пароля';
                    $mail->Body = "Для сброса пароля перейдите по <a href='localhost/Diplom/Registration/reset_password.php?token=$token'>этой ссылке</a>. Ссылка действительна в течение 1 часа.";

                    $mail->send();
                    echo json_encode(['status' => 'success', 'message' => 'Письмо для сброса пароля отправлено.']);
                } catch (Exception $e) {
                    error_log("Ошибка при отправке письма: {$mail->ErrorInfo}");
                    echo json_encode(['status' => 'error', 'message' => "Ошибка при отправке письма: {$mail->ErrorInfo}"]);
                }
                break;
            }
        }

        if (!$user_found) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь с таким email не найден.']);
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
    }
}

function encryptEmail($email, $key) {
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $encrypted = sodium_crypto_secretbox($email, $nonce, $key);
    return $nonce . $encrypted;
}

function decryptEmail($encrypted_email, $key) {
    $nonce = substr($encrypted_email, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $encrypted = substr($encrypted_email, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    return sodium_crypto_secretbox_open($encrypted, $nonce, $key);
}
?>
