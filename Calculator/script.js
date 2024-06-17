function toggleMenu() {
    var links = document.querySelector('.nav-links');
    links.classList.toggle('show');
  }

function calculateBMI() {
    var weight = parseFloat(document.getElementById('weight').value);
    var height = parseFloat(document.getElementById('height').value);
    if (!weight || !height) {
        alert('Пожалуйста, введите корректные значения веса и роста.');
        return;
    }

    var bmi = weight / ((height / 100) * (height / 100));
    document.getElementById('bmi-value').innerText = bmi.toFixed(2);

    var category;
    if (bmi < 16) category = "Выраженный дефицит массы тела";
    else if (bmi >= 16 && bmi < 18.5) category = "Недостаточная масса тела";
    else if (bmi >= 18.5 && bmi < 25) category = "Нормальная масса тела";
    else if (bmi >= 25 && bmi < 30) category = "Избыточная масса тела";
    else if (bmi >= 30 && bmi < 35) category = "Ожирение I степени";
    else if (bmi >= 35 && bmi < 40) category = "Ожирение II степени";
    else category = "Ожирение III степени";
    document.getElementById('category').innerText = category;

    var minNormalWeight = (18.5 * (height / 100) * (height / 100)).toFixed(2);
    var maxNormalWeight = (25 * (height / 100) * (height / 100)).toFixed(2);
    document.getElementById('normal-weight').innerText = `${minNormalWeight} - ${maxNormalWeight} кг `;

    document.getElementById('bmi-result').classList.remove('hidden');
    document.getElementById('bmi-result').classList.add('visible');
    document.getElementById('bmi-category').classList.remove('hidden');
    document.getElementById('bmi-category').classList.add('visible');
    document.getElementById('normal-range').classList.remove('hidden');
    document.getElementById('normal-range').classList.add('visible');

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
