<?php
require '../db.php'; 

$data = json_decode(file_get_contents("php://input"), true);

$schedule_id = $data['schedule_id'];
$title = $data['title'];
$trainer_name = $data['trainer_name'];
$description = $data['description'];
$start_time = $data['start_time'];
$end_time = $data['end_time'];
$capacity = $data['capacity'];

$stmt = $db->prepare("UPDATE workout_schedule SET title=?, trainer_name=?, description=?, start_time=?, end_time=?, capacity=? WHERE schedule_id=?");
$result = $stmt->execute([$title, $trainer_name, $description, $start_time, $end_time, $capacity, $schedule_id]);

if ($result) {
    echo "Запись обновлена успешно.";
} else {
    echo "Ошибка при обновлении записи.";
}
?>