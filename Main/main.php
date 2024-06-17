<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitClub</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans%3A400%2C400italic%2C600%2C700%2C700italic%7COswald%3A400%2C300%7CVollkorn%3A400%2C400italic'><link rel="stylesheet" href="style-slider.css">
</head>
<body>
<div class="wrapper">
    <header class="header">
    <div class="header_container">
    <nav class="navbar">
        <div class="logo">
            <img src="Images/logo.png" alt="FitClub Logo">
        </div>
        <button class="burger-menu" onclick="toggleMenu()"><img src="Images/burger.png" alt="Menu"></button>
        <div class="nav-links">
            <a href="..\Main\main.php">Главная</a>
            <a href="..\Shedule\shedule.php">Расписание</a>
            <a href="..\Abonement\abon.php">Тарифы</a>
            <a href="..\Questions\questions.php">Вопросы</a>
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
        <section class="title">
            <img class="title-image" src="../Main/Images/back2.jpg" alt="">
            <div class="title_container container">
            <div class="title_content">
                <h2>BMI CALCULATOR</h2>
                <p>Попробуйте удобный калькулятор для расчета </p>
                <p>индекса массы тела</p>
                <button onclick="redirectToCalculator()" class="read-more-btn">Попробовать</button>

            </div>
            </div>
        </section>
        <section class="about">
        <div class="about_container container">
        <h1 class="about_title">Что мы предлагаем</h1>
    <ul class="about_list">
        <li class="about_list-item">
            <div class="list_item-title">
                <h3 class="item_title-text">Тренажерный зал</h3>
            </div>
            <p class="list_item-text">Премиальное фитнес оборудование от итальянского бренда с мировым именем. Европейское качество каждой детали в тренажерах высочайшего качества.</p>
            <button onclick="openModal('gymModal')">Узнать больше ></button>
        </li>
        <li class="about_list-item">
            <div class="list_item-title">
                <h3 class="item_title-text">Групповые занятия</h3>
            </div>
            <p class="list_item-text">Всемирно известные групповые занятия, проработанные до мелочей. Каждый шаг, любое движение выверено и рассчитано ведущими в мире фитнес-специалистами.</p>
            <button onclick="openModal('classesModal')">Узнать больше ></button>
        </li>
        <li class="about_list-item">
            <div class="list_item-title">
                <h3 class="item_title-text">СПА-зона</h3>
            </div>
            <p class="list_item-text">Финская сауна и турецкий хамам, джакузи с панорамными окнами и соляная пещера после тренировки полноценно заменят сеанс релакс-массажа.</p>
            <button onclick="openModal('spaModal')">Узнать больше ></button>
        </li>
    </ul>
</div>
<section class="services-section">
    <div class="services-container">
        <div class="services-image">
            <img src="../Main/Images/service-pic.jpg" alt="">
        </div>
        <div class="services-text">
            <div class="column">
                <div class="service-item1">
                    <div class="text-content">
                        <img src="../Main/Images/service-icon-1.png" alt="">
                        <h4>Strategies</h4>
                        <p>Бла бла бла бла предлагаем стратегию бла бла бла </p>
                    </div>
                </div>
                <div class="service-item2">
                    <div class="text-content">
                        <img src="../Main/Images/service-icon-3.png" alt="">
                        <h4>Workout</h4>
                        <p>Бла бла бла бла предлагаем стратегию бла бла бла </p>
                    </div>
                </div>
            </div>
            <div class="column2">
                <div class="service-item3">
                    <div class="text-content">
                        <img src="../Main/Images/service-icon-2.png" alt="">
                        <h4>Yoga</h4>
                        <p>Бла бла бла бла предлагаем стратегию бла бла бла </p>
                    </div>
                </div>
                <div class="service-item4">
                    <div class="text-content">
                        <img src="../Main/Images/service-icon-4.png" alt="">
                        <h4>Weight Loss</h4>
                        <p>Бла бла бла бла предлагаем стратегию бла бла бла </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<main class="main-content">
            <div class="centered-title">
                <h1 class="about_title2">Наш зал</h1>
            </div>
  <section class="slideshow">
    <div class="slideshow-inner">
      <div class="slides">
        <div class="slide is-active ">
          <div class="slide-content">
            <div class="caption">
            <div class="title"></div>
            </div>
          </div>
          <div class="image-container"> 
            <img src="../Main/Images/slide1.jpg" alt="" class="image" />
          </div>
        </div>
        <div class="slide">
          <div class="slide-content">
            <div class="caption">
            <div class="title"></div>
            </div>
          </div>
          <div class="image-container">
          <img src="../Main/Images/slide2.jpg" alt="" class="image" />
          </div>
        </div>
        <div class="slide">
          <div class="slide-content">
            <div class="caption">
            <div class="title"></div>
            </div>
          </div>
          <div class="image-container">
          <img src="../Main/Images/slide3.jpg" alt="" class="image" />
          </div>
        </div>
        <div class="slide">
          <div class="slide-content">
            <div class="caption">
            <div class="title"></div>
            </div>
          </div>
          <div class="image-container"> 
          <img src="../Main/Images/slide4.jpg" alt="" class="image" />
          </div>
        </div>
      </div>
      <div class="pagination">
        <div class="item is-active"> 
          <span class="icon">1</span>
        </div>
        <div class="item">
          <span class="icon">2</span>
        </div>
        <div class="item">
          <span class="icon">3</span>
        </div>
        <div class="item">
          <span class="icon">4</span>
        </div>
      </div>
      <div class="arrows">
        <div class="arrow prev">
          <span class="svg svg-arrow-left">
            <svg version="1.1" id="svg4-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px" viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path d="M13,26c-0.256,0-0.512-0.098-0.707-0.293l-12-12c-0.391-0.391-0.391-1.023,0-1.414l12-12c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414L2.414,13l11.293,11.293c0.391,0.391,0.391,1.023,0,1.414C13.512,25.902,13.256,26,13,26z"/> </svg>
            <span class="alt sr-only"></span>
          </span>
        </div>
        <div class="arrow next">
          <span class="svg svg-arrow-right">
            <svg version="1.1" id="svg5-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px" viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path d="M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z"/> </svg>
            <span class="alt sr-only"></span>
          </span>
        </div>
      </div>
    </div> 
  </section>
  
</main>
    </main>
    <section class="promo-section">
    <div class="promo-item">
        <img src="../Main/Images/footer-banner-1.jpg" alt="New Member">
        <div class="promo-text">
            <h3>НОВЫМ ПОЛЬЗОВАТЕЛЯМ</h3>
            <h2>7 дней бесплатно!</h2>
            <p>Посещение бассейна и спа-зоны в подарок</p>
            <a href="../Abonement/abon.php" class="promo-button">Начать</a>
        </div>
    </div>
    <div class="promo-item">
        <img src="../Main/Images/footer-banner-2.jpg" alt="Contact Us">
        <div class="promo-text">
            <h3>СВЯЖИТЕСЬ С НАМИ</h3>
            <h2>+7 (918) 444 888</h2>
            <p>Ответим на любые интересующие вопросы</p>
            <a href="../Abonement/abon.php" class="promo-button">Начать</a>
        </div>
    </div>
</section>


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
</div>
<div id="gymModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('gymModal')">&times;</span>
        <div class="modal-header">
            <img src="Images/tren.jpg" alt="Тренажерный зал" class="modal-image">
            <div class="modal-text">
                <h2>Тренажерный зал</h2>
                <p>Супер-технологичный тренажерный зал, оборудованный в партнерстве с итальянской компанией Technogym - идеальное место для быстрого и безопасного достижения любых фитнес целей.</p>
                <p>Тренажерные залы клубов DDX отлично вентилируются, что предотвращает возникновение неприятных запахов.</p>
            </div>
        </div>
        <div class="modal-footer">
            <p>1700 Р в месяц</p>
            <a href="..\Abonement\abon.php" class="modal-button">Купить абонемент</a>
        </div>
    </div>
</div>
<div id="classesModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('classesModal')">&times;</span>
        <div class="modal-header">
            <img src="Images/group.jpg" alt="Тренажерный зал" class="modal-image">
            <div class="modal-text">
            <h2>Групповые занятия</h2>
            <p>Информация о групповых занятиях...</p>
            </div>
        </div>
        <div class="modal-footer">
            <p>1700 Р в месяц</p>
            <a href="..\Abonement\abon.php" class="modal-button">Купить абонемент</a>
        </div>
    </div>
</div>
<div id="spaModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('spaModal')">&times;</span>
        <div class="modal-header">
            <img src="Images/spa.jpg" alt="Тренажерный зал" class="modal-image">
            <div class="modal-text">
                <h2>СПА-зона</h2>
                <p>Информация о спа-зоне...</p>
                <p>Тренажерные залы клубов DDX отлично вентилируются, что предотвращает возникновение неприятных запахов.</p>
            </div>
        </div>
        <div class="modal-footer">
            <p>1700 Р в месяц</p>
            <a href="..\Abonement\abon.php" class="modal-button">Купить абонемент</a>
        </div>
    </div>
</div>

</div>
    <script  src="script.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js'></script><script  src="script-slider.js"></script>


</body>
</html>