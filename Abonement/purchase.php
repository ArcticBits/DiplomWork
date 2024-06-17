<?php

session_start();
require '../db.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $abonement_type = $_POST['abonement_type'];

    if (isset($_SESSION['users_id'])) {
        $users_id = $_SESSION['users_id'];
        $existingAbonementQuery = $db->prepare("SELECT * FROM purchased_abonements WHERE users_id = :users_id AND expiration_date >= CURDATE()");
        $existingAbonementQuery->execute(array(':users_id' => $users_id));
        $existingAbonement = $existingAbonementQuery->fetch(PDO::FETCH_ASSOC);

        if ($existingAbonement) {
            header("Location: abon.php?modal=active_abonement");
            exit();
        } else {
            $purchase_date = date("Y-m-d");
            $expiration_date = date('Y-m-d', strtotime($purchase_date . ' + 1 month'));
            $stmt = $db->prepare("INSERT INTO purchased_abonements (users_id, abonement_type, purchase_date, expiration_date) VALUES (:users_id, :abonement_type, :purchase_date, :expiration_date)");

            try {
                $stmt->execute(array(
                    ':users_id' => $users_id,
                    ':abonement_type' => $abonement_type,
                    ':purchase_date' => $purchase_date,
                    ':expiration_date' => $expiration_date
                ));
                header("Location: ../Dashboard/dashboard.php");
                exit();
            } catch (PDOException $e) {
                echo "Error executing query: " . $e->getMessage();
            }
        }
    } else {
        echo "User not logged in";
    }
}
?>
