<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: log.php");
    exit;
}

require '../db.php';

try {
    // получение данных 
    $title = $_POST['title'];
    $trainer_name = $_POST['trainer_name'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $capacity = $_POST['capacity'];
    
    // вставка новых данных в таблицу
    $sql = "INSERT INTO workout_schedule (title, description, trainer_name, start_time, end_time, capacity) VALUES (:title, :description, :trainer_name, :start_time, :end_time, :capacity)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':trainer_name', $trainer_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
    $stmt->execute();

    // перенаправление на админ панель
    header("Location: admin_panel.php?modal=schedule_updated");
} catch (PDOException $e) {
    die("ERROR: Не удалось выполнить $sql. " . $e->getMessage());
}
$db = null;
?>
