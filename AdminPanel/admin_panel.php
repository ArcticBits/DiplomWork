<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - SportLife</title>
    <link rel="stylesheet" href="admin_panel.css">
</head>
<body>
<div class="wrapper">
    <header class="header">
        <div class="header_container">
            <nav class="navbar">
                <div class="logo">
                    <img src="../Main/Images/logo.png" alt="FitClub Logo">
                </div>
                <button class="burger-menu" onclick="toggleMenu()"><img src="../Main/Images/burger.png" alt="Menu"></button>
                <div class="nav-links">
                    <a href="../Main/main.php">Главная</a>
                    <a href="../Shedule/shedule.php">Расписание</a>
                    <a href="../Abonement/abon.php">Тарифы</a>
                    <a href="../Questions/questions.php">Вопросы</a>

                    <?php
                    session_start();

                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                        echo '<a href="logout.php">Выход</a>';
                    } else {
                        echo '<a href="log.php">Вход</a>';
                    }
                    ?>
                </div>
            </nav>
        </div>
    </header>
</div>

<section class="dashboard">
    <div class="forms-container">
        <div class="schedule-forms">
            <form action="insert_schedule.php" method="post" class="form-section">
                <div class="form-section">
                    <h2>Добавление тренировок</h2>
                    <label for="title">Заголовок</label>
                    <input type="text" id="title" maxlength="100" name="title" required>

                    <label for="trainer_name">Имя тренера</label>
                    <select id="trainer_name" name="trainer_name" required>
                        <option value="">Выберите тренера</option>
                    </select>

                    <label for="description">Описание занятия</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="start_time">Дата и время начала</label>
                    <input type="datetime-local" id="start_time" name="start_time" required>

                    <label for="end_time">Дата и время окончания</label>
                    <input type="datetime-local" id="end_time" name="end_time" required>

                    <label for="capacity">Кол-во мест</label>
                    <input type="number" id="capacity" name="capacity" required>

                    <input type="submit" value="Сохранить">
                </div>
            </form>
        </div>
        <div class="schedule-table">
            <form action="get_schedule_data.php" method="post" class="form-section">
                <div class="form-section">
                    <h2>Существующие записи</h2>
                    <div class="schedule-table">
                        <div class="search-container" style="text-align: right; padding: 10px;">
                            <input type="text" id="searchInputSchedule" placeholder="Поиск по записям..." onkeyup="searchTable('searchInputSchedule', 'scheduleTable')">
                        </div>
                        <form action="get_schedule_data.php" method="post" class="form-section">
                        </form>
                    </div>

                    <table id="scheduleTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Заголовок</th>
                                <th>Имя тренера</th>
                                <th>Описание</th>
                                <th>Начало</th>
                                <th>Окончание</th>
                                <th>Вместимость</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require '../db.php'; 
                            // Вывод информации из таблицы расписания
                            try {
                                $query = "SELECT * FROM workout_schedule";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                                foreach ($result as $row) {
                                  echo "<tr data-id='{$row['schedule_id']}'>";
                                  echo "<td>{$row['schedule_id']}</td>";
                                  echo "<td contenteditable='true'>" . htmlspecialchars($row['title']) . "</td>";
                                  echo "<td class='trainer-select'>" . htmlspecialchars($row['trainer_name']) . "</td>";
                                  echo "<td contenteditable='true'>" . htmlspecialchars($row['description']) . "</td>";
                                  echo "<td contenteditable='true'>" . htmlspecialchars($row['start_time']) . "</td>";
                                  echo "<td contenteditable='true'>" . htmlspecialchars($row['end_time']) . "</td>";
                                  echo "<td contenteditable='true'>" . htmlspecialchars($row['capacity']) . "</td>";
                                  echo "<td><button onclick='updateRecord(this.parentElement.parentElement)' class='save-btn'>Обновить</button></td>";
                                  echo "<td><button onclick='deleteRecord({$row['schedule_id']})' class='delete-btn'>Удалить</button></td>";
                                  echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo "Ошибка выполнения запроса: " . $e->getMessage();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="questions">
    <div class="questions-table">
        <div class="form-section">
            <h2>Существующие записи</h2>
            <div class="search-container" style="text-align: right; padding: 10px;">
                <input type="text" id="searchInputQuestions" placeholder="Поиск по вопросам..." onkeyup="searchTable('searchInputQuestions', 'questionsTable')">
            </div>
            <table id="questionsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Вопрос пользователя</th>
                        <th>Ответ</th>
                        <th>Телефон</th>
                        <th>Имя</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require '../db.php'; 
                    // Вывод информации из таблицы вопросов 
                    $key_path = '../encryption.key';
                    if (!file_exists($key_path)) {
                        die('Encryption key not found.');
                    }
                    $key = file_get_contents($key_path);
                    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
                        die('Invalid key size.');
                    }
                    
                    try {
                        $query = "SELECT * FROM user_questions";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        foreach ($result as $row) {
                            // Расшифровка номера телефона
                            $phone_data = $row['phone_number'];
                            if (strlen($phone_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                                $nonce = substr($phone_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $encrypted_phone_number = substr($phone_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $decrypted_phone_number = sodium_crypto_secretbox_open($encrypted_phone_number, $nonce, $key);

                                if ($decrypted_phone_number === false) {
                                    $decrypted_phone_number = "Ошибка расшифровки";
                                }
                            } else {
                                $decrypted_phone_number = "Неверные данные";
                            }

                            echo "<tr data-id='{$row['question_id']}'>";
                            echo "<td>" . htmlspecialchars($row['question_id']) . "</td>";
                            echo "<td contenteditable='true'>" . htmlspecialchars($row['question_text']) . "</td>";
                            echo "<td contenteditable='true'>" . htmlspecialchars($row['answer_text']) . "</td>";
                            echo "<td>" . htmlspecialchars($decrypted_phone_number) . "</td>";
                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                            echo "<td><button onclick='updateQuestion(this.parentElement.parentElement)' class='save-btn'>Обновить</button></td>";
                            echo "<td><button onclick='deleteQuestion({$row['question_id']})' class='delete-btn'>Удалить</button></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Ошибка выполнения запроса: " . $е->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="users">
    <div class="users-table">
        <div class="form-section">
            <h2>Пользователи</h2>
            <div class="search-container" style="text-align: right; padding: 10px;">
                <input type="text" id="searchInputUsers" placeholder="Поиск по пользователям..." onkeyup="searchTable('searchInputUsers', 'usersTable')">
            </div>
            <div class="button-container" style="text-align: center; padding: 10px;">
                <button onclick="showAllTrainers()" class="show-btn">Показать всех тренеров</button>
                <button onclick="showAllRecords()" class="show-btn">Показать все записи</button>
            </div>
            <div class="search-container" style="text-align: right; padding: 10px;">
                <input type="text" id="searchInputUsers" placeholder="Поиск по пользователям..." onkeyup="searchTable('searchInputUsers', 'usersTable')">
            </div>
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Роль</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require '../db.php';
                    $key_path = '../encryption.key';
                    if (!file_exists($key_path)) {
                        die('Encryption key not found.');
                    }
                    $key = file_get_contents($key_path);
                    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
                        die('Invalid key size.');
                    }
                    
                    try {
                        $query = "SELECT * FROM users";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        foreach ($result as $row) {
                            // Расшифровка email
                            $email_data = $row['email'];
                            if (strlen($email_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                                $nonce = substr($email_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $encrypted_email = substr($email_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $decrypted_email = sodium_crypto_secretbox_open($encrypted_email, $nonce, $key);

                                if ($decrypted_email === false) {
                                    $decrypted_email = "Ошибка расшифровки";
                                }
                            } else {
                                $decrypted_email = "Неверные данные";
                            }

                            // Расшифровка имени
                            $first_name_data = $row['first_name'];
                            if (strlen($first_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                                $nonce_first_name = substr($first_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $encrypted_first_name = substr($first_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $decrypted_first_name = sodium_crypto_secretbox_open($encrypted_first_name, $nonce_first_name, $key);

                                if ($decrypted_first_name === false) {
                                    $decrypted_first_name = "Ошибка расшифровки";
                                }
                            } else {
                                $decrypted_first_name = "Неверные данные";
                            }

                            // Расшифровка фамилии
                            $last_name_data = $row['last_name'];
                            if (strlen($last_name_data) >= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                                $nonce_last_name = substr($last_name_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $encrypted_last_name = substr($last_name_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                                $decrypted_last_name = sodium_crypto_secretbox_open($encrypted_last_name, $nonce_last_name, $key);

                                if ($decrypted_last_name === false) {
                                    $decrypted_last_name = "Ошибка расшифровки";
                                }
                            } else {
                                $decrypted_last_name = "Неверные данные";
                            }

                            echo "<tr data-id='{$row['users_id']}'>";
                            echo "<td>" . htmlspecialchars($row['users_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($decrypted_email) . "</td>";
                            echo "<td>" . htmlspecialchars($decrypted_first_name) . "</td>";
                            echo "<td>" . htmlspecialchars($decrypted_last_name) . "</td>";
                            echo "<td>
                                    <select onchange='updateUserRole(this)' data-id='{$row['users_id']}'" . ($row['role'] == 'admin' ? ' disabled' : '') . ">
                                        <option value='user'" . ($row['role'] == 'user' ? ' selected' : '') . ">Пользователь</option>
                                        <option value='trainer'" . ($row['role'] == 'trainer' ? ' selected' : '') . ">Тренер</option>";
                            if ($row['role'] == 'admin') {
                                echo "<option value='admin' selected>Администратор</option>";
                            }
                            echo "</select>
                                  </td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Ошибка выполнения запроса: " . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="script.js"></script>
</body>
</html>
