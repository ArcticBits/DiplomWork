<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(120);

require '../vendor/autoload.php';
require '../db.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Функция для отправки письма
function sendNotification($email, $training_time) {
    $mail = new PHPMailer(true);
    
    try {
        // Настройки сервера
        $mail->isSMTP();
        $mail->Host = 'ssl://smtp.mail.ru'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'sportlife03@mail.ru'; 
        $mail->Password = 'mtwDuJLs9CMc6a0iNSur'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Получатели
        $mail->setFrom('sportlife03@mail.ru', 'Sportlife');
        $mail->addAddress($email);

        // Содержимое письма
        $mail->isHTML(true);
        $mail->Subject = 'Trainig';
        $mail->Body    = 'Уважаемый клиент! Напоминаем что вы записаны на тренировку ' . $training_time . '! С уважением команда Sportlife.';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

// определение времени до тренировки
$current_time = new DateTime();
$time_in_2_hours = clone $current_time;
$time_in_2_hours->modify('+2 hours');
$time_in_3_hours = clone $current_time;
$time_in_3_hours->modify('+3 hours');

$time_in_2_hours_formatted = $time_in_2_hours->format('Y-m-d H:i:s');
$time_in_3_hours_formatted = $time_in_3_hours->format('Y-m-d H:i:s');

echo "Текущее время: " . $current_time->format('Y-m-d H:i:s') . "<br>";
echo "Время через 2 часа: " . $time_in_2_hours_formatted . "<br>";
echo "Время через 3 часа: " . $time_in_3_hours_formatted . "<br>";

try {
    $stmt = $db->prepare("SELECT u.email, ws.start_time, pw.workout_id, pw.users_id FROM purchased_workout pw 
                          JOIN users u ON pw.users_id = u.users_id
                          JOIN workout_schedule ws ON pw.workout_id = ws.schedule_id
                          WHERE ws.start_time > :time_in_2_hours AND ws.start_time <= :time_in_3_hours
                          AND pw.notification_sent = FALSE");
    $stmt->execute([':time_in_2_hours' => $time_in_2_hours_formatted, ':time_in_3_hours' => $time_in_3_hours_formatted]);

    if ($stmt->rowCount() > 0) {
        // Отправка уведомлений всем пользователям
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Расшифровка электронной почты
            $nonce = substr($row['email'], 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_email = substr($row['email'], SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_email = sodium_crypto_secretbox_open($encrypted_email, $nonce, $key);
            
            if ($decrypted_email === false) {
                echo "Failed to decrypt email for user ID " . $row['users_id'] . "<br>";
                continue;
            }

            sendNotification($decrypted_email, $row['start_time']);
            
            // Обновление статуса уведомления в базе данных
            $update_stmt = $db->prepare("UPDATE purchased_workout SET notification_sent = TRUE 
                                         WHERE workout_id = :workout_id AND users_id = :users_id");
            $update_stmt->execute([':workout_id' => $row['workout_id'], ':users_id' => $row['users_id']]);
        }
    } else {
        echo "No upcoming trainings";
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>