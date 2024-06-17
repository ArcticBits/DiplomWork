<?php
session_start();
require '../db.php'; 

$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Ключ ширфования не найден.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

// Получаем ID тренера из сессии
$trainer_id = $_SESSION['users_id'];

try {
    $query = "SELECT first_name, last_name FROM users WHERE users_id = :trainer_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':trainer_id', $trainer_id, PDO::PARAM_INT);
    $stmt->execute();
    $trainer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($trainer) {
        // дешифровка имени
        $first_name_data = $trainer['first_name'];
        if (strlen($first_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $nonce = substr($first_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_first_name = substr($first_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_first_name = sodium_crypto_secretbox_open($encrypted_first_name, $nonce, $key);

            if ($decrypted_first_name === false) {
                die('Failed to decrypt first name.');
            }
        } else {
            die('Invalid encrypted first name.');
        }

        // дешифровка фамилии
        $last_name_data = $trainer['last_name'];
        if (strlen($last_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $nonce = substr($last_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_last_name = substr($last_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_last_name = sodium_crypto_secretbox_open($encrypted_last_name, $nonce, $key);

            if ($decrypted_last_name === false) {
                die('Failed to decrypt last name.');
            }
        } else {
            die('Invalid encrypted last name.');
        }

        // Устанавливаем имя и фамилию тренера в сессию, если они еще не установлены
        if (!isset($_SESSION['first_name'])) {
            $_SESSION['first_name'] = $decrypted_first_name;
        }
        if (!isset($_SESSION['last_name'])) {
            $_SESSION['last_name'] = $decrypted_last_name;
        }

        echo htmlspecialchars($_SESSION['first_name']) . '<br>';
    } else {
        echo "Тренер не найден.";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
