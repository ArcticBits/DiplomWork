<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitClub</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/ru.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <div class="dashboard">
        <div class="welcome-message">
            <h1>Добро пожаловать, <span id="username"><?php include 'get_user_info.php'; ?></span>!</h1>
        </div>
        <div class="dashboard-container">
            <div class="dashboard-section">
                <h2 class="section-title">Ваш абонемент</h2>
                <?php include 'abon_dash.php'; ?>
            </div>
            <div class="dashboard-section">
                <h2 class="section-title">Ваши ближайшие тренировки</h2>
                <div class="workout-cards scrollable">
                    <?php include 'schedule_dash.php'; ?>
                </div>
            </div>
        </div>
        <div class="dashboard-section">
            <h2 class="section-title">Ваши результаты ИМТ</h2>
            <canvas id="bmiChart"></canvas>
        </div>
    </div>
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
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Вы уверены, что хотите отменить запись?</p>
        <form id="cancelForm">
            <input type="hidden" id="cancel_workout_id" name="workout_id">
            <button type="button" class="cancel-button" onclick="cancelWorkout()">Отменить</button>
            <button type="button" class="no-cancel-button" onclick="closeModal()">Оставить</button>
        </form>
    </div>
</div>
<div id="notification" class="notification"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    fetch('get_bmi_results.php')
        .then(response => response.json())
        .then(data => {
            var ctx = document.getElementById('bmiChart').getContext('2d');
            window.bmiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'ИМТ',
                        data: data.bmi,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointBorderColor: 'rgba(255, 255, 255, 1)',
                        pointStyle: 'circle'
                    }]
                },
                options: {
                    onClick: function(e) {
                        var activePoints = bmiChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                        if (activePoints.length > 0) {
                            var clickedElementIndex = activePoints[0].index;
                            var id = data.ids[clickedElementIndex];
                            var date = bmiChart.data.labels[clickedElementIndex];
                            var bmi = bmiChart.data.datasets[0].data[clickedElementIndex];
                            var confirmed = confirm(`Вы хотите удалить запись с датой ${date} и ИМТ ${bmi}?`);
                            if (confirmed) {
                                deleteBMIResult(id);
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        });
});

function deleteBMIResult(id) {
    fetch('delete_bmi_result.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Запись успешно удалена.');
            updateBMIChart();
        } else {
            alert('Ошибка при удалении записи.');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

function updateBMIChart() {
    fetch('get_bmi_results.php')
        .then(response => response.json())
        .then(data => {
            bmiChart.data.labels = data.dates;
            bmiChart.data.datasets[0].data = data.bmi;
            bmiChart.update();
        });
}

function openModal(workoutId) {
    document.getElementById('cancel_workout_id').value = workoutId;
    document.getElementById('confirmationModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

function cancelWorkout() {
    const workoutId = document.getElementById('cancel_workout_id').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'cancel_booking.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            showNotification(xhr.responseText);
            closeModal();
            location.reload();
        } else {
            showNotification('Ошибка при отмене записи. Пожалуйста, попробуйте еще раз.');
        }
    };
    xhr.send('workout_id=' + workoutId);
}


function showNotification(message) {
    const notification = document.getElementById('notification');
    notification.innerText = message;
    notification.style.display = 'block';
    setTimeout(() => {
        notification.style.display = 'none';
    }, 5000);
}

function upgradeAbonement(newAbonementType) {
    const currentAbonementType = document.querySelector('.item_title-text').innerText;
    const confirmUpgrade = confirm(`Вы уверены, что хотите улучшить абонемент с ${currentAbonementType} до ${newAbonementType}?`);
    if (confirmUpgrade) {
        window.location.href = `upgrade.php?current_abonement_type=${currentAbonementType}&new_abonement_type=${newAbonementType}`;
    }
}

</script>
<script src="script.js"></script>
</body>
</html>
