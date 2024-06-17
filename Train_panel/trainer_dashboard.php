<?php
session_start();
require '../db.php';

if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header('Location: log.php');
    exit();
}

$trainer_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет тренера - FitClub</title>
    <link rel="stylesheet" href="train.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/ru.min.js"></script>
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
    <div class="dashboard">
        <div class="welcome-message">
            <h1>Добро пожаловать, <span id="username"><?php echo htmlspecialchars($trainer_name); ?></span></h1>
        </div>
        <div class="dashboard-container">
            <div class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Ваши тренировки</h2>
                    <form id="search-form" action="trainer_dashboard.php" method="GET" class="search-form-inline">
                        <label for="search-date">Выберите дату:</label>
                        <input type="date" id="search-date" name="search_date">
                        <button type="submit">Найти</button>
                    </form>
                </div>
                <div class="workout-cards">
                    <?php include 'trainer_schedule_dash.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="footer_container container">
            <div class="footer_address">
                <p>Адрес клуба: г.Ростов-на-Дону, ул.Текучева 34</p>
            </div>
            <div class="footer_socials">
                <a href="whatsapp_url" target="_blank"><img src="..\Main\Images\what2.png" alt="WhatsApp"></a>
                <a href="vkontakte_url" target="_blank"><img src="..\Main\Images\vk2.png" alt="VKontakte"></a>
                <a href="mailto:fit.club@gmail.com"><img src="..\Main\Images\tg2.png" alt="Inst"></a>
            </div>
            <p class="adress">©2024 ООО "ФитнесСтор" | Fit,LLC. Все права защищены</p>
            <div class="footer_contacts">
                <p>Контактная информация: +78005553535, fit.club@gmail.com</p>
            </div>
        </div>
    </footer>
</div>
<script src="script.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    moment.locale('ru'); 

    var startTimes = document.querySelectorAll('.start-time');
    var endTimes = document.querySelectorAll('.end-time');

    startTimes.forEach(function(element) {
        var date = moment(element.innerHTML);
        element.innerHTML = date.format('D MMMM HH:mm');
    });

    endTimes.forEach(function(element) {
        var date = moment(element.innerHTML);
        element.innerHTML = date.format('D MMMM HH:mm');
    });
});
</script>
</body>
</html>
