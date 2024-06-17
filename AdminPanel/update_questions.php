<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: log.php");
    exit;
}

require '../db.php'; 

try {
    $question_id = $_POST['question_id'];
    $answer_text = $_POST['answer'];

    // Проверка входных данных
    if (empty($question_id) || empty($answer_text)) {
        throw new Exception('Отсутствуют необходимые данные.');
    }

    // обновление данных в таблице
    $sql = "UPDATE user_questions SET answer_text = :answer WHERE question_id = :question_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':answer', $answer_text);
    $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Ответ успешно обновлен.";
    } else {
        throw new Exception('Не удалось выполнить запрос.');
    }
} catch (PDOException $e) {
    die("ERROR: Не удалось выполнить $sql. " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}

// Close connection
$db = null;
?>
