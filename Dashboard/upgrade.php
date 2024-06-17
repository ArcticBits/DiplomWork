<?php
session_start();
require '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $current_abonement_type = $_GET['current_abonement_type'];
    $new_abonement_type = $_GET['new_abonement_type'];

    if (isset($_SESSION['users_id'])) {
        $users_id = $_SESSION['users_id'];
        $amount_difference = 0;

        // разницы доплат
        if ($current_abonement_type === 'Light' && $new_abonement_type === 'Standart') {
            $amount_difference = 500;
        } elseif ($current_abonement_type === 'Light' && $new_abonement_type === 'Premium') {
            $amount_difference = 1000;
        } elseif ($current_abonement_type === 'Standart' && $new_abonement_type === 'Premium') {
            $amount_difference = 500;
        } else {
            echo "Неверный запрос на улучшение абонемента";
            exit();
        }

        try {
            // Инициация платежа через ЮKassa (тестовый режим)
            $data = [
                'amount' => [
                    'value' => $amount_difference,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'http://localhost/Diplom/Dashboard/callback_upgrade.php?new_abonement_type=' . $new_abonement_type . '&users_id=' . $users_id,
                ],
                'capture' => true,
                'description' => 'Оплата за улучшение абонемента до ' . $new_abonement_type,
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