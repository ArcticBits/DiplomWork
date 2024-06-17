<?php

require '../db.php'; 
$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Проверка, существует ли пользователь с такой электронной почтой
        $check_sql = "SELECT email FROM users";
        $stmt = $db->prepare($check_sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            $stored_email = $user['email'];
            if (strlen($stored_email) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                continue; // Пропуск некорректной записи
            }
            $stored_nonce = substr($stored_email, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $stored_ciphertext = substr($stored_email, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_email = sodium_crypto_secretbox_open($stored_ciphertext, $stored_nonce, $key);

            if ($decrypted_email === false) {
                continue; // Пропуск неудачной расшифровки
            }

            if ($decrypted_email === $email) {
                echo "Email already registered";
                exit;
            }
        }

        // Хэширование пароля 
        $hashed_password = sodium_crypto_pwhash_str(
            $password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );
        $hashed_password_blob = sodium_bin2hex($hashed_password);

        // Шифрование данных
        function encrypt_data($data, $key) {
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            return $nonce . sodium_crypto_secretbox($data, $nonce, $key);
        }

        $encrypted_email = encrypt_data($email, $key);
        $encrypted_first_name = encrypt_data($first_name, $key);
        $encrypted_last_name = encrypt_data($last_name, $key);
        $sql = "INSERT INTO users (last_name, first_name, email, password) VALUES (:last_name, :first_name, :email, :password)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':last_name', $encrypted_last_name, PDO::PARAM_LOB);
        $stmt->bindParam(':first_name', $encrypted_first_name, PDO::PARAM_LOB);
        $stmt->bindParam(':email', $encrypted_email, PDO::PARAM_LOB); 
        $stmt->bindParam(':password', $hashed_password_blob, PDO::PARAM_LOB);
        if ($stmt->execute()) {
            echo "success"; 
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
