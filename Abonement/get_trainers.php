<?php
require '../db.php';

try {
    $query = "SELECT first_name, last_name FROM users WHERE role = 'trainer'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $trainers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($trainers);
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
