<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['users_id'];
    $newRole = $_POST['role'];

    // Валидация новой роли
    $valid_roles = ['admin', 'trainer', 'user'];
    if (!in_array($newRole, $valid_roles)) {
        echo "Недопустимая роль.";
        exit();
    }

    try {
        $query = "UPDATE users SET role = :role WHERE users_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':role', $newRole, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        echo "Роль пользователя успешно обновлена!";
    } catch (PDOException $e) {
        echo "Ошибка обновления роли: " . $e->getMessage();
    }
} else {
    echo "Неверный метод запроса.";
}
?>
