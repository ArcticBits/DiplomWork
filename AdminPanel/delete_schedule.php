<?php
require '../db.php'; 

$schedule_id = $_POST['schedule_id'];

try {
    $stmt = $db->prepare("DELETE FROM workout_schedule WHERE schedule_id = ?");
    $stmt->execute([$schedule_id]);
    echo "Запись удалена";
} catch (PDOException $e) {
    echo "Ошибка при удалении записи: " . $e->getMessage();
}
?>
