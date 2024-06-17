<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitClub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <link rel="stylesheet" href="shedule.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/ru.js"></script>
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
                    session_start();
                    $role=$_SESSION['role'];
                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                        if ($role == 'admin') {
                            echo '<a href="../AdminPanel/admin_panel.php">Панель администратора</a>';
                        } elseif ($role == 'trainer') {
                            echo '<a href="../Train_panel/dashboard.php">Личный кабинет тренера</a>';
                        } else {
                            echo '<a href="../Dashboard/dashboard.php">Личный кабинет</a>';
                        }
                    } else {
                        echo '<a href="../Registration/log.html" class="login-button">Вход</a>';
                    }
                    ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="schedule-container">
            <div id="calendar"></div>
        </div>
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="workoutTitle"></h2>
        <p><strong>Тренер:</strong> <span id="trainerName"></span></p>
        <p><strong>Описание:</strong> <span id="workoutDescription"></span></p>
        <p><strong>Дата и время:</strong> <span id="workoutTime"></span></p>
        <p><strong>Кол-во мест:</strong> <span id="capacity"></span></p>
        <input type="hidden" id="workout_id">
        <button id="bookWorkoutBtn">Записаться</button>
    </div>
</div>
        <section class="trainers-section">
            <h2 class="about_title">Наши тренеры</h2>
            <div class="trainers-container">
                <div class="trainer-card">
                    <div class="trainer-card-inner">
                        <div class="trainer-card-front">
                            <img src="Images/1.jpg" alt="Patrick Cortez">
                        </div>
                        <div class="trainer-card-back">
                            <h3>Сергей Дажук</h3>
                            <p class="role">Leader</p>
                            <p>Описание тренера, квалификация и достижения.</p>
                            <div class="social-icons">
                                <a href="#"><img src="Images/tg.png" alt="telegram"></a>
                                <a href="#"><img src="Images/inst.png" alt="Instagram"></a>
                                <a href="#"><img src="Images/vk.png" alt="Vkontakte"></a>
                                <a href="#"><img src="Images/what.png" alt="Whatsapp"></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info-bottom">
                        <h4>Сергей Дажук</h4>
                        <p class="role">Главный тренер</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-card-inner">
                        <div class="trainer-card-front">
                            <img src="Images/2.jpg" alt="Patrick Cortez">
                        </div>
                        <div class="trainer-card-back">
                            <h3>Виктория Смирнова</h3>
                            <p class="role">Тренер по гимнастике</p>
                            <p>Описание тренера, квалификация и достижения.</p>
                            <div class="social-icons">
                                <a href="#"><img src="Images/tg.png" alt="telegram"></a>
                                <a href="#"><img src="Images/inst.png" alt="Instagram"></a>
                                <a href="#"><img src="Images/vk.png" alt="Vkontakte"></a>
                                <a href="#"><img src="Images/what.png" alt="Whatsapp"></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info-bottom">
                        <h4>Виктория Смирнова</h4>
                        <p class="role">Тренер по гимнастике</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-card-inner">
                        <div class="trainer-card-front">
                            <img src="Images/4.jpg" alt="Gregory Powers">
                        </div>
                        <div class="trainer-card-back">
                            <h3>Екатирина Филимонова</h3>
                            <p class="role">Gym coach</p>
                            <p>Описание тренера, квалификация и достижения.</p>
                            <div class="social-icons">
                                <a href="#"><img src="Images/tg.png" alt="telegram"></a>
                                <a href="#"><img src="Images/inst.png" alt="Instagram"></a>
                                <a href="#"><img src="Images/vk.png" alt="Vkontakte"></a>
                                <a href="#"><img src="Images/what.png" alt="Whatsapp"></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info-bottom">
                        <h4>Екатерина Филимонова</h4>
                        <p class="role">Тренер по йоге</p>
                    </div>
                </div>
                <div class="trainer-card">
                    <div class="trainer-card-inner">
                        <div class="trainer-card-front">
                            <img src="Images/3.jpg" alt="Walter Wagner">
                        </div>
                        <div class="trainer-card-back">
                            <h3>Мирослав Андреев</h3>
                            <p>Занимаюсь пауэрлифтингом более 5 лет, являюсь чемпионом России. Имею широкий опыт работы с людьим и составления тренировок.</p>
                            <div class="social-icons">
                                <a href="#"><img src="Images/tg.png" alt="telegram"></a>
                                <a href="#"><img src="Images/inst.png" alt="Instagram"></a>
                                <a href="#"><img src="Images/vk.png" alt="Vkontakte"></a>
                                <a href="#"><img src="Images/what.png" alt="Whatsapp"></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info-bottom">
                        <h4>Мирослав Андреев</h4>
                        <p class="role">Тренер по пауэрлифтингу</p>
                    </div>
                </div>
            </div>
            <div class="promo-container">
                <div class="promo-text">
                    <h2>НАЧНИ ТРЕНИРОВКУ СЕГОДНЯ!</h2>
                    <p>Купи абонемент, запишись на занятие к интересующему тренеру или свяжись с ним по ссылке в карточке.</p>
                    <button onclick="location.href='../Abonement/abon.php'">Начать</button>
                </div>
                <img src="Images/banner-bg.jpg" alt="Promotion Image" class="promo-bg">
            </div>

        </section>
        <!-- Карта -->
        <section class="map-section">
            <h2 class="about_title2"> Как нас найти<h2>
            <div id="map"></div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer_container container">
            <div class="footer_address">
                <p>Адрес клуба: г.Ростов-на-Дону, ул.Текучева 34</p>
            </div>
            <div class="footer_copyright">
                <div class="footer_socials">
                    <a href="whatsapp_url" target="_blank"><img src="../Main/Images/what2.png" alt="WhatsApp"></a>
                    <a href="vkontakte_url" target="_blank"><img src="../Main/Images/vk2.png" alt="VKontakte"></a>
                    <a href="mailto:fit.club@gmail.com"><img src="../Main/Images/tg2.png" alt="Inst"></a>
                </div>
                <p class="adress">©2024 ООО "ФитнесСтор" | Fit,LLC. Все права защищены</p>
            </div>
            <div class="footer_contacts">
                <p>Контактная информация: +78005553535, fit.club@gmail.com</p>
            </div>
        </div>
    </footer>
</div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="script.js"></script>
<script type="text/javascript">
ymaps.ready(init);
function init(){
    var myMap = new ymaps.Map("map", {
        center: [47.2214, 39.7104], 
        zoom: 15
    });

    var myPlacemark = new ymaps.Placemark([47.2214, 39.7104], {
        hintContent: 'Фитнес-клуб',
        balloonContent: 'Фитнес-клуб: г.Ростов-на-Дону, ул.Текучева 34'
    });

    myMap.geoObjects.add(myPlacemark);
}
</script>
</body>
</html>