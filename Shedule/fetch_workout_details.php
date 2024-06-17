<?php
session_start();

require '../db.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $workout_id = intval($_POST['workout_id']); 
    $response = [];

    try {
        $sql = "SELECT * FROM workout_schedule WHERE schedule_id = :workout_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $response = [
                'id' => $row['schedule_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'trainer_name' => $row['trainer_name'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'capacity' => $row['capacity'] 
            ];
        }

        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode(['error' => "Ошибка в SQL-запросе: " . $e->getMessage()]);
    }
}
?>
