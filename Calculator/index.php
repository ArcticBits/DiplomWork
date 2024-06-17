<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор ИМТ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
                    <a href="../Abonement/abon.php">Тарифы</а>
                    <a href="../Questions/questions.php">Вопросы</a>
                    <?php
                    session_start();
                    $role = $_SESSION['role'];
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
    <div class="calculator">
        <h2>Калькулятор ИМТ</h2>
        <p>Индекс массы тела (ИМТ) — это число, которое рассчитывается на основе вашего веса и роста. Оно используется для классификации подкатегорий недостатка веса, нормального веса, избыточного веса и ожирения, что может указывать на различные риски для здоровья.</p>
    
        <input type="number" id="weight" placeholder="Вес в кг">
        <input type="number" id="height" placeholder="Рост в см">
        <button onclick="calculateBMI()">Рассчитать ИМТ</button>
        <div id="bmi-result" class="hidden">ИМТ: <span id="bmi-value"></span></div>
        <div id="bmi-category" class="hidden">Категория: <span id="category"></span></div>
        <div id="normal-range" class="hidden">Нормальный диапазон веса: <span id="normal-weight"></span> кг</div>
        <div id="save-result" class="hidden">
            <button onclick="saveBMI()">Сохранить результат</button>
        </div>
        
        <div class="scale">
            <div class="arrow" id="arrow"></div>
            <div class="color-scale">
                <div class="label" style="left: 5%"> < 16</div>
                <div class="label" style="left: 20%">16 - 18,5</div>
                <div class="disc" style="left: 15%"> Дефицит</div>
                <div class="label" style="left: 50%">18,5 - 25</div>
                <div class="disc" style="left: 50%"> Норма</div>
                <div class="label" style="left: 80%">25 - 30</div>
                <div class="disc" style="left: 80%"> Избыток </div>
                <div class="label" style="left: 94%"> > 30</div>
                <div class="disc" style="left: 95%"> Ожирение </div>
            </div>
        </div>
        <div class="statistics">
            <h3>Статистика по расчетам ИМТ</h3>
            <canvas id="bmiChart"></canvas>
        </div>
    </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function calculateBMI() {
    var weight = parseFloat(document.getElementById('weight').value);
    var height = parseFloat(document.getElementById('height').value) / 100;
    if (isNaN(weight) || isNaN(height)) {
        alert('Пожалуйста, введите корректные значения веса и роста.');
        return;
    }
    var bmi = weight / (height * height);
    bmi = bmi.toFixed(2);
    document.getElementById('bmi-value').textContent = bmi;
    document.getElementById('bmi-result').classList.remove('hidden');
    document.getElementById('bmi-category').classList.remove('hidden');
    document.getElementById('normal-range').classList.remove('hidden');
    var category = '';
    if (bmi < 16) {
        category = 'Выраженный дефицит массы';
    } else if (bmi >= 16 && bmi < 18.5) {
        category = 'Недостаток массы';
    } else if (bmi >= 18.5 && bmi < 25) {
        category = 'Норма';
    } else if (bmi >= 25 && bmi < 30) {
        category = 'Избыточная масса';
    } else {
        category = 'Ожирение';
    }
    document.getElementById('category').textContent = category;
    var normalWeightMin = 18.5 * (height * height);
    var normalWeightMax = 24.9 * (height * height);
    document.getElementById('normal-weight').textContent = normalWeightMin.toFixed(2) + ' - ' + normalWeightMax.toFixed(2);
    
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        document.getElementById('save-result').classList.remove('hidden');
    <?php endif; ?>
    updateArrowPosition(bmi);
}

function updateArrowPosition(bmi) {
    var scaleWidth = document.querySelector('.scale').clientWidth;
    var position = 0;
    if (bmi < 16) position = (bmi / 16) * 10;
    else if (bmi < 18.5) position = 10 + ((bmi - 16) / (18.5 - 16)) * 20;
    else if (bmi < 25) position = 30 + ((bmi - 18.5) / (25 - 18.5)) * 40;
    else if (bmi < 30) position = 70 + ((bmi - 25) / (30 - 25)) * 20;
    else position = 90 + ((Math.min(bmi, 40) - 30) / (40 - 30)) * 10;
    document.getElementById('arrow').style.left = `${position}%`;
}

function saveBMI() {
    var bmi = document.getElementById('bmi-value').textContent;
    var category = document.getElementById('category').textContent;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_bmi.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Результат сохранен.');
        } else {
            alert('Ошибка при сохранении результата.');
        }
    };
    xhr.send('bmi=' + bmi + '&category=' + category);
}

document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('bmiChart').getContext('2d');
    var bmiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Выраженный дефицит (<16)', 'Недостаток массы (16 - 18,5)', 'Норма (18,5 - 25)', 'Избыток массы (25 - 30)', 'Ожирение I ст. (30 - 35)', 'Ожирение II ст. (35 - 40)', 'Ожирение III ст. (>40)'],
            datasets: [{
                label: 'Процентное соотношение пользователей',
                data: [1, 3, 35, 29, 19, 10, 3], 
                backgroundColor: [
                    'rgba(255, 99, 132, 0.4)',
                    'rgba(255, 159, 64, 0.4)',
                    'rgba(75, 192, 192, 0.4)',
                    'rgba(255, 206, 86, 0.4)',
                    'rgba(153, 102, 255, 0.4)',
                    'rgba(255, 159, 64, 0.4)',
                    'rgba(255, 99, 132, 0.4)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
</body>
</html>