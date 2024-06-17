<?php
require '../db.php'; 

try {
    $sql = "SELECT schedule_id, title, start_time, end_time, capacity FROM workout_schedule";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];
    foreach ($result as $row) {
        $events[] = [
            'id' => $row['schedule_id'],
            'title' => $row['title'],
            'start' => $row['start_time'],
            'end' => $row['end_time']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($events);
} catch (PDOException $e) {
    die("Ошибка в SQL-запросе: " . $e->getMessage());
}
?>
