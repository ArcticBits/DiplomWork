<?php

session_start();

require '../db.php'; 
require 'roles.php'; 
$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $email = htmlspecialchars($email);

    try {
        // Подготовка запроса для получения всех пользователей
        $query = "SELECT * FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $user_found = false;

        foreach ($users as $row) {
            $nonce = substr($row['email'], 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_email = substr($row['email'], SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_email = sodium_crypto_secretbox_open($encrypted_email, $nonce, $key);
            $hashed_password_blob = sodium_hex2bin($row['password']);
            
            if ($decrypted_email === $email && sodium_crypto_pwhash_str_verify($hashed_password_blob, $password)) {
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['users_id'] = $row['users_id'];
                $_SESSION['role'] = $row['role'];
                $user_found = true;
                switch ($row['role']) {
                    case Roles::ADMIN:
                        header("Location: ../AdminPanel/admin_panel.php");
                        break;
                    case Roles::TRAINER:
                        header("Location: ../Train_panel/dashboard.php");
                        break;
                    case Roles::USER:
                        header("Location: ../Dashboard/dashboard.php");
                        break;
                    default:
                        header("Location: log.html?modal=active_abonement");
                        break;
                }
                exit();
            }
        }

        if (!$user_found) {
            header("Location: log.html?modal=active_abonement");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
