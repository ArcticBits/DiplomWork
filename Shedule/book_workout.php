<?php
session_start();

require '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $user_id = $_SESSION['users_id'];
        $workout_id = intval($_POST['workout_id']); 

        if (!empty($workout_id) && is_numeric($workout_id)) {
            try {
                $check_existing = "SELECT * FROM purchased_workout WHERE workout_id = :workout_id AND users_id = :user_id";
                $stmt = $db->prepare($check_existing);
                $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $existing_result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing_result) {
                    echo "Вы уже записаны на эту тренировку.";
                } else {
                    $sql = "SELECT abonement_type FROM purchased_abonements WHERE users_id = :user_id AND expiration_date >= CURDATE()";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        $abonement_type = $row['abonement_type'];
                        if (in_array($abonement_type, ['Standart', 'Premium'])) {
                            $capacity_sql = "SELECT capacity FROM workout_schedule WHERE schedule_id = :workout_id";
                            $stmt = $db->prepare($capacity_sql);
                            $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                            $stmt->execute();
                            $capacity_row = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($capacity_row && $capacity_row['capacity'] > 0) {
                                $db->beginTransaction();
                                $insert_sql = "INSERT INTO purchased_workout (workout_id, users_id) VALUES (:workout_id, :user_id)";
                                $stmt = $db->prepare($insert_sql);
                                $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                if ($stmt->execute()) {
                                    $update_capacity_sql = "UPDATE workout_schedule SET capacity = capacity - 1 WHERE schedule_id = :workout_id";
                                    $stmt = $db->prepare($update_capacity_sql);
                                    $stmt->bindParam(':workout_id', $workout_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $db->commit();
                                    echo "Вы успешно записались на тренировку!";
                                } else {
                                    $db->rollBack();
                                    echo "Ошибка: " . $stmt->errorInfo()[2];
                                }
                            } else {
                                echo "Нет доступных мест на эту тренировку.";
                            }
                        } else {
                            echo "У вас нет необходимого уровня абонемента для записи на эту тренировку.";
                        }
                    } else {
                        echo "У вас нет активного абонемента.";
                    }
                }
            } catch (PDOException $e) {
                echo "Ошибка в SQL-запросе: " . $e->getMessage();
            }
        } else {
            echo "Неверный идентификатор тренировки.";
        }
    } else {
        echo "Пожалуйста, авторизуйтесь, чтобы записаться на тренировку.";
    }
}
?>
