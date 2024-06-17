<?php
session_start();
require '../db.php'; 

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $users_id = $_SESSION['users_id'];
    $bmi = $_POST['bmi'];
    $category = $_POST['category'];

    try {
        $sql = "INSERT INTO bmi_results (users_id, bmi, category, created_at) VALUES (:users_id, :bmi, :category, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
        $stmt->bindParam(':bmi', $bmi, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "not_logged_in";
}
?>
