<?php
session_start();
require '../db.php'; 

$user_id = $_SESSION['users_id'];

try {
    $sql = "SELECT id, DATE_FORMAT(created_at, '%Y-%m-%d') as date, bmi FROM bmi_results WHERE users_id = :users_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dates = [];
    $bmi = [];
    $ids = [];
    foreach ($results as $result) {
        $dates[] = $result['date'];
        $bmi[] = $result['bmi'];
        $ids[] = $result['id'];
    }

    echo json_encode(['dates' => $dates, 'bmi' => $bmi, 'ids' => $ids]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
