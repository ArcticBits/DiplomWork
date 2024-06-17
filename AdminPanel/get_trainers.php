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

try {
    $query = "SELECT first_name, last_name FROM users WHERE role = 'trainer'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $trainers = [];
    foreach ($result as $row) {
        $first_name_data = $row['first_name'];
        $last_name_data = $row['last_name'];

        // Дешифровка имени
        if (strlen($first_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $nonce = substr($first_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_first_name = substr($first_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_first_name = sodium_crypto_secretbox_open($encrypted_first_name, $nonce, $key);

            if ($decrypted_first_name === false) {
                continue;
            }
        } else {
            continue;
        }

        // Decrypt фамилии
        if (strlen($last_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $nonce = substr($last_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $encrypted_last_name = substr($last_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $decrypted_last_name = sodium_crypto_secretbox_open($encrypted_last_name, $nonce, $key);

            if ($decrypted_last_name === false) {
                continue;
            }
        } else {
            continue;
        }

        $full_name = $decrypted_first_name . ' ' . $decrypted_last_name;
        $trainers[] = ['full_name' => $full_name];
    }

    header('Content-Type: application/json');
    echo json_encode($trainers);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
