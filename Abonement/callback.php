<?php
session_start();
require '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['abonement_type'])) {
    if (isset($_SESSION['users_id'])) {
        $users_id = $_SESSION['users_id'];
        $abonement_type = $_GET['abonement_type'];
        $purchase_date = date("Y-m-d");
        $expiration_date = date('Y-m-d', strtotime($purchase_date . ' + 1 month'));
        $status = 'Куплен';

        try {
            // Проверка на наличие активного абонемента
            $stmt = $db->prepare("SELECT COUNT(*) FROM purchased_abonements WHERE users_id = :users_id AND expiration_date > NOW()");
            $stmt->execute(array(':users_id' => $users_id));
            $active_abonement_count = $stmt->fetchColumn();
            if ($active_abonement_count > 0) {
                header("Location: abon.php?modal=active_abonement");
                exit();
            }

            // Вставка нового абонемента
            $stmt = $db->prepare("INSERT INTO purchased_abonements (users_id, abonement_type, purchase_date, expiration_date, status) VALUES (:users_id, :abonement_type, :purchase_date, :expiration_date, :status)");
            $stmt->execute(array(
                ':users_id' => $users_id,
                ':abonement_type' => $abonement_type,
                ':purchase_date' => $purchase_date,
                ':expiration_date' => $expiration_date,
                ':status' => $status
            ));
            header("Location: ../Dashboard/dashboard.php");
            exit();
        } catch (PDOException $e) {
            echo "Ошибка выполнения запроса: " . $e->getMessage();
        }
    } else {
        echo "Пользователь не авторизован или сессия недействительна.";
    }
} else {
    echo "Неверный метод запроса или отсутствует тип абонемента.";
}
?>
