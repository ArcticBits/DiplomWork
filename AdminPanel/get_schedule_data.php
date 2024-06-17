<?php

require '../db.php'; 
$sql = "SELECT * FROM workout_schedule";
try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $schedule_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка при выполнении запроса: " . $e->getMessage());
}
echo '<form class="existing-schedule-form">';
foreach ($schedule_data as $row) {
    echo '<label for="schedule_' . $row['schedule_id'] . '">Занятие: ' . $row['title'] . '</label>';
    echo '<input type="text" id="schedule_' . $row['schedule_id'] . '" value="' . $row['title'] . '" readonly>';
}
echo '</form>';
$db = null;
?>
