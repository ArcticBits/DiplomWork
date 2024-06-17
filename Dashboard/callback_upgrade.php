<?php
session_start();
require '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $new_abonement_type = $_GET['new_abonement_type'];
    $users_id = $_GET['users_id'];

    try {
        // Обновление типа абонемента пользователя
        $stmt = $db->prepare("UPDATE purchased_abonements SET abonement_type = :new_abonement_type WHERE users_id = :users_id AND expiration_date > NOW()");
        $stmt->execute(array(':new_abonement_type' => $new_abonement_type, ':users_id' => $users_id));

        // Перенаправление пользователя на страницу подтверждения
        header("Location: ../Dashboard/dashboard.php?modal=upgrade_success");
        exit();
    } catch (PDOException $e) {
        echo 'Ошибка базы данных: ' . $e->getMessage();
    }
} else {
    echo "Неверный запрос";
}
?>
