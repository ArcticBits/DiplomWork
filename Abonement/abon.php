<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitClub - Тарифы</title>
    <link rel="stylesheet" href="abon.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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

    <main class="main">
        <section class="about">
            <div class="about_container container">
                <h1 class="about_title">Наши абонементы</h1>
                <ul class="about_list">
                    <li class="about_list-item">
                        <div class="list_item-title">
                            <h3 class="item_title-text light">Light</h3>
                            <img src="..\Main\Images\light.png" alt="Изображение" class="item_image">
                        </div>
                        <p class="list_item-text checked">Безлимитный доступ в клуб</p>
                        <p class="list_item-text checked">Тренажерный зал</p>
                        <p class="list_item-text checked">Анализ состава тела InBody</p>
                        <p class="list_item-text checked">Бесплатные занятия с тренером</p>
                        <p class="list_item-text unchecked">Групповые занятия</p>
                        <p class="list_item-text unchecked">СПА зона</p>
                        <p class="list_item-text unchecked">Доступ для друзей</p>
                        <p class="list_item-text unchecked">Семейный доступ</p>
                        <span class="price">1500 руб.</span> 
                        <form class="purchase-form" action="payment.php" method="get">
                            <input type="hidden" name="abonement_type" value="Light">
                            <button type="button" class="btn-light btn" onclick="checkAuth(event)">Купить</button>
                        </form>
                    </li>
                    <li class="about_list-item">
                        <div class="list_item-title">
                            <h3 class="item_title-text stan">Standart</h3>
                            <img src="..\Main\Images\standart.png" alt="Изображение" class="item_image">
                        </div>
                        <p class="list_item-text checked">Безлимитный доступ в клуб</p>
                        <p class="list_item-text checked">Тренажерный зал</p>
                        <p class="list_item-text checked">Анализ состава тела InBody</p>
                        <p class="list_item-text checked">Бесплатные занятия с тренером</p>
                        <p class="list_item-text checked">Групповые занятия</p>
                        <p class="list_item-text unchecked">СПА зона</p>
                        <p class="list_item-text unchecked">Доступ для друзей</p>
                        <p class="list_item-text unchecked">Семейный доступ</p>
                        <span class="price">2000 руб.</span> 
                        <form class="purchase-form" action="payment.php" method="get">
                            <input type="hidden" name="abonement_type" value="Standart">
                            <button type="button" class="btn-standart btn" onclick="checkAuth(event)">Купить</button>
                        </form>
                    </li>
                    <li class="about_list-item">
                        <div class="list_item-title">
                            <h3 class="item_title-text prem">Premium</h3>
                            <img src="..\Main\Images\premium.png" alt="Изображение" class="item_image">
                        </div>
                        <p class="list_item-text checked">Безлимитный доступ в клуб</p>
                        <p class="list_item-text checked">Тренажерный зал</p>
                        <p class="list_item-text checked">Анализ состава тела InBody</p>
                        <p class="list_item-text checked">Бесплатные занятия с тренером</p>
                        <p class="list_item-text checked">Групповые занятия</p>
                        <p class="list_item-text checked">СПА зона</p>
                        <p class="list_item-text checked">Доступ для друзей</p>
                        <p class="list_item-text checked">Семейный доступ</p>
                        <span class="price">2500 руб.</span>
                        <form class="purchase-form" action="payment.php" method="get">
                            <input type="hidden" name="abonement_type" value="Premium">
                            <button type="button" class="btn-premium btn" onclick="checkAuth(event)">Купить</button>
                        </form>
                    </li>
                </ul>
            </div>
        </section>
        
        <section class="contact">
            <div class="contact_text">
                <p><span class="count" data-count="1000">0</span>+ довольных клиентов</p>
                <p><span class="count" data-count="4">0</span> профессиональных тренера</p>
                <p>Удобное расположение</p>
            </div>
        </section>


    </main>


    <footer class="footer">
        <div class="footer_container container">
            <div class="footer_address">
                <p>Адрес клуба: г.Ростов-на-Дону, ул.Текучева 34</p>
            </div>
            <div class="footer_copyright">
                <div class="footer_socials">
                    <a href="whatsapp_url" target="_blank"><img src="..\Main\Images\what2.png" alt="WhatsApp"></a>
                    <a href="vkontakte_url" target="_blank"><img src="..\Main\Images\vk2.png" alt="VKontakte"></a>
                    <a href="mailto:fit.club@gmail.com"><img src="..\Main\Images\tg2.png" alt="Inst"></a>
                </div>
                <p class="adress">©2024 ООО "ФитнесСтор" | Fit,LLC. Все права защищены</p>
            </div>
            <div class="footer_contacts">
                <p>Контактная информация: +78005553535, fit.club@gmail.com</p>
            </div>
        </div>
    </footer>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
            <button class="btn btn-success">ОК</button>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('modal') && urlParams.get('modal') === 'active_abonement') {
        showModal('У вас уже есть активный абонемент. Вы не можете купить новый, пока действует старый.');
    }
}

function checkAuth(event) {
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        var form = event.target.closest('form');
        form.submit();
    <?php else: ?>
        showModal('Вы должны войти в систему, чтобы купить абонемент.');
    <?php endif; ?>
}

function showModal(message) {
    var modal = document.getElementById('myModal');
    var modalMessage = document.getElementById('modal-message');
    modalMessage.textContent = message;
    modal.style.display = "block";
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    var closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            modal.style.display = 'none'; 
        });
    }
    
    var okButton = document.querySelector('.btn-success');
    if (okButton) {
        okButton.addEventListener('click', function() {
            modal.style.display = 'none'; 
        });
    }
}
</script>
<script src="script.js"></script>
</body>
</html>