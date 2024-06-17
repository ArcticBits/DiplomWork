<?php
session_start();

require '../db.php'; 

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['users_id'];

    try {
        $sql = "SELECT * FROM purchased_abonements WHERE users_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo '<div class="about">';
            echo '<div class="about_container">';
            echo '<ul class="about_list">';

            foreach ($result as $row) {
                echo '<li class="about_list-item">';
                echo '<div class="list_item-title">';
                echo '<h3 class="item_title-text">' . htmlspecialchars($row['abonement_type']) . '</h3>';
                echo '<img src="..\Main\Images\\' . strtolower($row['abonement_type']) . '.png" alt="Изображение" class="item_image">';
                echo '</div>';

                if ($row['abonement_type'] === "Light") {
                    echo '<p class="list_item-text checked">Безлимитный доступ в клуб</p>';
                    echo '<p class="list_item-text checked">Тренажерный зал</p>';
                    echo '<p class="list_item-text checked">Анализ состава тела InBody</p>';
                    echo '<p class="list_item-text checked">Бесплатные занятия с тренером</p>';
                    echo '<p class="list_item-text unchecked">Групповые занятия</p>';
                    echo '<p class="list_item-text unchecked">СПА зона</p>';
                    echo '<p class="list_item-text unchecked">Доступ для друзей</p>';
                    echo '<p class="list_item-text unchecked">Семейный доступ</p>';
                } elseif ($row['abonement_type'] === "Standart") {
                    echo '<p class="list_item-text checked">Безлимитный доступ в клуб</p>';
                    echo '<p class="list_item-text checked">Тренажерный зал</p>';
                    echo '<p class="list_item-text checked">Анализ состава тела InBody</p>';
                    echo '<p class="list_item-text checked">Бесплатные занятия с тренером</p>';
                    echo '<p class="list_item-text checked">Групповые занятия</p>';
                    echo '<p class="list_item-text unchecked">СПА зона</p>';
                    echo '<p class="list_item-text unchecked">Доступ для друзей</p>';
                    echo '<p class="list_item-text unchecked">Семейный доступ</p>';
                } elseif ($row['abonement_type'] === "Premium") {
                    echo '<p class="list_item-text checked">Безлимитный доступ в клуб</p>';
                    echo '<p class="list_item-text checked">Тренажерный зал</p>';
                    echo '<p class="list_item-text checked">Анализ состава тела InBody</p>';
                    echo '<p class="list_item-text checked">Бесплатные занятия с тренером</p>';
                    echo '<p class="list_item-text checked">Групповые занятия</p>';
                    echo '<p class="list_item-text checked">СПА зона</p>';
                    echo '<p class="list_item-text checked">Доступ для друзей</p>';
                    echo '<p class="list_item-text checked">Семейный доступ</p>';
                }

                echo '<p class="list_item-text">Дата покупки: ' . date('d.m.Y', strtotime($row['purchase_date'])) . '</p>';
                echo '<p class="list_item-text">Дата истечения: ' . date('d.m.Y', strtotime($row['expiration_date'])) . '</p>';
                
                // Форма улучшения абонемента
                if ($row['abonement_type'] !== "Premium") {
                    echo '<div class="upgrade-container">';
                    echo '<form method="GET" action="upgrade.php" class="upgrade-form">';
                    echo '<input type="hidden" name="current_abonement_type" value="' . htmlspecialchars($row['abonement_type']) . '">';
                    echo '<select name="new_abonement_type" required>';
                    echo '<option value="" disabled selected>Выберите новый тариф</option>';
                    if ($row['abonement_type'] === "Light") {
                        echo '<option value="Standart">Standart (Доплата 500 руб.)</option>';
                        echo '<option value="Premium">Premium (Доплата 1000 руб.)</option>';
                    } elseif ($row['abonement_type'] === "Standart") {
                        echo '<option value="Premium">Premium (Доплата 500 руб.)</option>';
                    }
                    echo '</select>';
                    echo '<button type="submit" class="upgrade-button">Улучшить</button>';
                    echo '</form>';
                    echo '</div>';
                }

                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div style="text-align: center; margin-top:40px; ">Нет активного абонемента</div>';
        }
    } catch (PDOException $e) {
        echo "Ошибка при выполнении запроса: " . $e->getMessage();
    }
} else {
    echo '<a href="log.php">Вход</a>';
}
?>
