<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitClub - Тарифы</title>
    <link rel="stylesheet" href="questions.css">
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
    <section class="faq">
        <?php
        include '../db.php'; 

        $query = "SELECT question_text, answer_text FROM user_questions WHERE answer_text IS NOT NULL AND answer_text <> ''";
        $statement = $db->prepare($query);
        $statement->execute();

        $faq_items = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="container">
            <h2>ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ</h2>
            <?php foreach ($faq_items as $item): ?>
                <div class="accordion">
                    <span class="accordion-text"><?php echo htmlspecialchars($item['question_text']); ?></span>
                    <img src="arrow1.png" class="arrow">
                </div>
                <div class="panel">
                    <p><?php echo nl2br(htmlspecialchars($item['answer_text'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="ask-question">
        <img class="other-img" src="title1.png" alt="Другая фотография">
        <div class="container" style="display: inline-block;">
            <h2>ОТВЕТИЛИ НА ТВОЙ ВОПРОС?</h2>
            <p>Если нет — напиши нам</p>
            <form action="submit_question.php" method="POST">
                <input type="text" name="full_name" placeholder="ТВОЕ ИМЯ" required>
                <input type="text" name="phone_number" placeholder="ТЕЛЕФОН" required>
                <textarea name="question" placeholder="ТВОЙ ВОПРОС" required></textarea>
                <button type="submit">ОТПРАВИТЬ</button>
                <p class="form-disclaimer">Нажимая на кнопку, Вы даёте согласие на обработку персональных данных и соглашаетесь с политикой конфиденциальности</p>
            </form>
        </div>
    </section>
</div>
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
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Спасибо за обратную связь!</p>
        <button class="btn btn-success">ОК</button> 
    </div>
</div>
<script>
window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('modal') && urlParams.get('modal') === 'active_abonement') {
    var modal = document.getElementById('myModal');
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
}

document.addEventListener("DOMContentLoaded", function() {
    var arrows = document.getElementsByClassName("arrow");
    for (var i = 0; i < arrows.length; i++) {
        arrows[i].addEventListener("click", function(event) {
            var accordion = this.parentElement;
            accordion.classList.toggle("active");
            var panel = accordion.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
            event.stopPropagation(); 
        });
    }
});
</script>
<script src="script.js"></script>
</body>
</html>