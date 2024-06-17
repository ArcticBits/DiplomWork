<?php
session_start();

if (isset($_SESSION['users_id'])) {
    require '../db.php'; 

    $users_id = $_SESSION['users_id'];

    $key_path = '../encryption.key';
    if (!file_exists($key_path)) {
        die('Encryption key not found.');
    }
    $key = file_get_contents($key_path);
    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        die('Invalid key size.');
    }

    try {
        $query = "SELECT first_name FROM users WHERE users_id = :users_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Извлечение nonce и зашифрованного имени
            $nonce = substr($row['first_name'], 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_first_name = substr($row['first_name'], SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

            // Расшифровка имени
            $decrypted_first_name = sodium_crypto_secretbox_open($encrypted_first_name, $nonce, $key);

            if ($decrypted_first_name === false) {
                echo "Ошибка: Не удалось расшифровать имя";
            } else {
                echo htmlspecialchars($decrypted_first_name);
            }
        } else {
            echo "Ошибка: Имя пользователя не найдено";
        }
    } catch (PDOException $e) {
        die("Ошибка в SQL-запросе: " . $e->getMessage());
    }
    $db = null;
} else {
    echo "Ошибка: Пользователь не аутентифицирован";
}
?>
