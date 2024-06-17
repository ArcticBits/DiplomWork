<?php
session_start();

if (!isset($_SESSION['users_id'])) {
    die('User is not logged in.');
}

$userId = $_SESSION['users_id'];
require '../db.php'; 

$sql = "SELECT ws.schedule_id, ws.title, ws.description, ws.trainer_name, ws.start_time, ws.end_time 
        FROM workout_schedule ws
        JOIN purchased_workout pw ON ws.schedule_id = pw.workout_id
        WHERE pw.users_id = :userId";

$stmt = $db->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();

$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($workouts) > 0) {
    foreach ($workouts as $workout) {
        echo "<div class='schedule-card'>";
        echo "<div class='schedule-header'><span class='schedule-title'>" . htmlspecialchars($workout['title']) . "</span></div>";
        echo "<div class='schedule-description'><strong>Описание:</strong> " . htmlspecialchars($workout['description']) . "</div>";
        echo "<div class='schedule-info'><strong>Тренер:</strong> " . htmlspecialchars($workout['trainer_name']) . "</div>";
        echo "<div class='schedule-time'><strong>Дата и время начала:</strong> <span class='start-time'>" . htmlspecialchars($workout['start_time']) . "</span></div>";
        echo "<div class='schedule-time'><strong>Дата и время окончания:</strong> <span class='end-time'>" . htmlspecialchars($workout['end_time']) . "</span></div>";
        echo "<button class='cancel-button' onclick='openModal(" . htmlspecialchars($workout['schedule_id']) . ")'>Отменить запись</button>";
        echo "</div>";
    }
} else {
    echo "<div class='schedule-no-classes'>У Вас нет активных записей.</div>";
}

echo "<script>var workouts = " . json_encode($workouts) . ";</script>";
?>
