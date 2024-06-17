<?php
session_start();
require '../db.php';

if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    echo "<p class='schedule-no-classes'>Имя тренера не задано в сессии.</p>";
    exit();
}

$trainer_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

try {
    $query = "SELECT ws.*, 
                     (ws.capacity - COALESCE(pw.registered_count, 0)) AS remaining_capacity 
              FROM workout_schedule ws
              LEFT JOIN (
                  SELECT workout_id, COUNT(*) AS registered_count 
                  FROM purchased_workout 
                  GROUP BY workout_id
              ) pw ON ws.schedule_id = pw.workout_id
              WHERE ws.trainer_name = :trainer_name";
    
    if ($search_date) {
        $query .= " AND DATE(ws.start_time) = :search_date";
    }
    
    $query .= " ORDER BY ws.start_time ASC";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':trainer_name', $trainer_name, PDO::PARAM_STR);
    if ($search_date) {
        $stmt->bindParam(':search_date', $search_date, PDO::PARAM_STR);
    }
    $stmt->execute();
    $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($workouts)) {
        echo "<p class='schedule-no-classes'>Нет запланированных тренировок.</p>";
    } else {
        foreach ($workouts as $workout) {
            echo "<div class='schedule-card'>";
            echo "<div class='schedule-header'>";
            echo "<h3 class='schedule-title'>" . htmlspecialchars($workout['title']) . "</h3>";
            echo "</div>";
            echo "<div class='schedule-info'>"; 

            echo "<p class='schedule-time'><span class='start-time'>" . htmlspecialchars($workout['start_time']) . "</span> - <span class='end-time'>" . htmlspecialchars($workout['end_time']) . "</span></p>";
            echo "<p class='schedule-capacity'>Кол-во мест:" . htmlspecialchars($workout['remaining_capacity']) . " / " . htmlspecialchars($workout['capacity']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
