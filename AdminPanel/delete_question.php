<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: log.php");
    exit;
}

require_once "../db.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];

    if (empty($question_id)) {
        http_response_code(400);
        echo 'ID вопроса не указан.';
        exit();
    }

    try {
        $sql = "DELETE FROM user_questions WHERE question_id = :question_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo 'Вопрос успешно удален.';
        } else {
            http_response_code(404);
            echo 'Вопрос с таким ID не найден.';
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Ошибка выполнения запроса: ' . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo 'Метод не поддерживается.';
}

unset($db);
?>
