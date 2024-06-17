<?php
session_start();
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $abonement_type = $_GET['abonement_type'];
    if (isset($_SESSION['users_id'])) {
        $users_id = $_SESSION['users_id'];
        $amount = 0;
        switch ($abonement_type) {
            case 'Light':
                $amount = 1500;
                break;
            case 'Standart':
                $amount = 2000;
                break;
            case 'Premium':
                $amount = 2500;
                break;
            default:
                echo "Неверный тип абонемента";
                exit();
        }
        try {
            // Проверка на наличие активного абонемента
            $stmt = $db->prepare("SELECT COUNT(*) FROM purchased_abonements WHERE users_id = :users_id AND expiration_date > NOW() AND status = 'Куплен'");
            $stmt->execute(array(':users_id' => $users_id));
            $active_abonement_count = $stmt->fetchColumn();

            if ($active_abonement_count > 0) {
                header("Location: abon.php?modal=active_abonement");
                exit();
            }

            // Инициация платежа через ЮKassa (тестовый режим)
            $data = [
                'amount' => [
                    'value' => $amount,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'http://localhost/Diplom/Abonement/callback.php?abonement_type=' . $abonement_type . '&users_id=' . $users_id,
                ],
                'capture' => true,
                'description' => 'Оплата за абонемент ' . $abonement_type,
            ];

            $ch = curl_init('https://api.yookassa.ru/v3/payments');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Idempotence-Key: ' . uniqid(),
                'Authorization: Basic ' . base64_encode('389471:test_tUZul6yoDe9pBt8aR59gGy5RQvJGdL6ZJGmFaq3ZxqQ'), 
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                echo 'Ошибка CURL: ' . curl_error($ch);
                exit();
            }

            curl_close($ch);

            if ($http_code === 200) {
                $response_data = json_decode($response, true);
                $confirmation_url = $response_data['confirmation']['confirmation_url'];

                header("Location: $confirmation_url");
                exit();
            } else {
                echo "Инициация платежа не удалась. Код ответа: $http_code. Ответ: $response";
            }
        } catch (PDOException $e) {
            echo 'Ошибка базы данных: ' . $e->getMessage();
        }
    } else {
        echo "Пользователь не авторизован";
    }
}
?>