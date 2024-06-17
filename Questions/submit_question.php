<?php
include '../db.php'; 

$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'])) {

    $question_text = trim($_POST['question']);
    $phone_number = trim($_POST['phone_number']); 
    $full_name = trim($_POST['full_name']);

    // Шифрование номера телефона
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $encrypted_phone_number = $nonce . sodium_crypto_secretbox($phone_number, $nonce, $key);

    $query = "INSERT INTO user_questions (question_text, phone_number, full_name) VALUES (:question_text, :phone_number, :full_name)";
    $statement = $db->prepare($query);
    
    $statement->bindValue(':question_text', $question_text);
    $statement->bindValue(':phone_number', $encrypted_phone_number, PDO::PARAM_LOB);
    $statement->bindValue(':full_name', $full_name);
    
    try {
        $statement->execute();
        header("Location: questions.php?modal=active_abonement");
    } catch (PDOException $e) {
        echo "Ошибка при отправке вопроса: " . $e->getMessage();
    }
} else {
    header('Location: ask_question_form.php');
    exit;
}
?>
