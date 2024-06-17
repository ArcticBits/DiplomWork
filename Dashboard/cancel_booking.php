<?php
session_start();

require '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $user_id = $_SESSION['users_id'];
        $workout_id = intval($_POST['workout_id']); 

        if (!empty($workout_id) && is_numeric($workout_id)) {
            try {
                //проверка наличия записи
                $check_existing = "SELECT * FROM purchased_workout WHERE workout_id = :workout_id AND users_id = :user_id";
                $stmt = $db->prepare($check_existing);
                $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $existing_result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing_result) {
                    // отмена записи
                    $db->beginTransaction();
                    $delete_sql = "DELETE FROM purchased_workout WHERE workout_id = :workout_id AND users_id = :user_id";
                    $stmt = $db->prepare($delete_sql);
                    $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    if ($stmt->execute()) {
                        // изменения счета записавшихся
                        $update_capacity_sql = "UPDATE workout_schedule SET capacity = capacity + 1 WHERE schedule_id = :workout_id";
                        $stmt = $db->prepare($update_capacity_sql);
                        $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $db->commit();
                    } else {
                        $db->rollBack();
                        echo "Ошибка: " . $stmt->errorInfo()[2];
                    }
                } else {
                    echo "Вы не записаны на эту тренировку.";
                }
            } catch (PDOException $e) {
                echo "Ошибка в SQL-запросе: " . $e->getMessage();
            }
        } else {
            echo "Неверный идентификатор тренировки.";
        }
    }
}
?>
