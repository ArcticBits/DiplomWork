
/*Burger*/
function toggleMenu() {
  var links = document.querySelector('.nav-links');
  links.classList.toggle('show');
}

function deleteRecord(scheduleId) {
  if (confirm('Вы уверены, что хотите удалить эту запись?')) {
      fetch('delete_schedule.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'schedule_id=' + scheduleId
      })
      .then(response => response.text())
      .then(data => {
          alert('Запись удалена!');
          window.location.reload(); 
      })
      .catch(error => alert('Ошибка удаления: ' + error));
  }
}

function updateRecord(row) {
  event.preventDefault();
  const scheduleId = row.getAttribute('data-id');
  const trainerSelect = row.querySelector('select.trainer-select');
  const data = {
      schedule_id: scheduleId,
      title: row.children[1].innerText,
      trainer_name: trainerSelect ? trainerSelect.value : row.children[2].innerText,
      description: row.children[3].innerText,
      start_time: row.children[4].innerText,
      end_time: row.children[5].innerText,
      capacity: row.children[6].innerText
  };

  fetch('update_schedule.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
  })
  .then(response => response.text())
  .then(result => {
      alert('Запись успешно обновлена!');
  })
  .catch(error => console.error('Ошибка:', error));
}

function searchTable(inputId, tableId) {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById(inputId);
  filter = input.value.toUpperCase();
  table = document.getElementById(tableId); 
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td");
      var found = false;
      for (var j = 0; j < td.length; j++) {
          if (td[j]) {
              txtValue = td[j].textContent || td[j].innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  found = true;
                  break;
              }
          }
      }
      if (found) {
          tr[i].style.display = "";
      } else {
          tr[i].style.display = "none";
      }
  }
}

function deleteQuestion(questionId) {
  if (confirm('Вы уверены, что хотите удалить этот вопрос?')) {
      fetch('delete_question.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'question_id=' + questionId
      })
      .then(response => {
          if (!response.ok) {
              return response.text().then(text => { throw new Error(text) });
          }
          return response.text();
      })
      .then(data => {
          alert('Вопрос удален!');
          window.location.reload(); 
      })
      .catch(error => {
          console.error('Ошибка удаления:', error);
          alert('Ошибка удаления: ' + error.message);
      });
  }
}

function updateQuestion(row) {
  const questionId = row.getAttribute('data-id');
  const data = {
      question_id: questionId,
      answer: row.children[2].innerText 
  };

  fetch('update_questions.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(data).toString()
  })
  .then(response => response.text())
  .then(result => {
      alert('Вопрос успешно обновлен!');
      console.log(result); // Для отладки
  })
  .catch(error => console.error('Ошибка:', error));
}

function updateUserRole(selectElement) {
  const userId = selectElement.getAttribute('data-id');
  const newRole = selectElement.value;

  if (newRole === 'admin') {
      alert('Невозможно назначить роль Администратор новым пользователям.');
      selectElement.value = selectElement.getAttribute('data-original-role');
      return;
  }

  const data = {
      users_id: userId,
      role: newRole
  };

  fetch('update_user_role.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(data).toString()
  })
  .then(response => response.text())
  .then(result => {
      alert('Роль пользователя успешно обновлена!');
      selectElement.setAttribute('data-original-role', newRole);
      console.log(result); // Для отладки
  })
  .catch(error => console.error('Ошибка:', error));
}

function showAllTrainers() {
  var table, tr, td, select, i;
  table = document.getElementById("usersTable");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[4];
      if (td) {
          select = td.getElementsByTagName("select")[0];
          if (select && select.value === "trainer") {
              tr[i].style.display = "";
          } else {
              tr[i].style.display = "none";
          }
      }
  }
}

function showAllRecords() {
  var table, tr, i;
  table = document.getElementById("usersTable");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
      tr[i].style.display = "";
  }
}

document.addEventListener("DOMContentLoaded", function() {
  fetchTrainers();
  setOriginalRoles();
});

function fetchTrainers() {
  fetch('get_trainers.php')
  .then(response => response.json())
  .then(data => {
      const trainerSelect = document.getElementById('trainer_name');
      data.forEach(trainer => {
          const option = document.createElement('option');
          option.value = trainer.full_name;
          option.text = trainer.full_name;
          trainerSelect.appendChild(option);
      });

      // опции для редактируемых строк таблицы
      const rows = document.querySelectorAll('#scheduleTable tbody tr');
      rows.forEach(row => {
          const cell = row.querySelector('.trainer-select');
          const currentTrainer = cell.textContent;
          const select = document.createElement('select');
          select.classList.add('trainer-select'); 
          data.forEach(trainer => {
              const option = document.createElement('option');
              option.value = trainer.full_name;
              option.text = trainer.full_name;
              if (trainer.full_name === currentTrainer) {
                  option.selected = true;
              }
              select.appendChild(option);
          });
          cell.innerHTML = ''; // Очистка содержимого ячейки перед добавлением нового
          cell.appendChild(select);
      });
  })
  .catch(error => console.error('Ошибка загрузки тренеров:', error));
}

function setOriginalRoles() {
  const roleSelects = document.querySelectorAll('#usersTable tbody tr select');
  roleSelects.forEach(select => {
      const originalRole = select.value;
      select.setAttribute('data-original-role', originalRole);
  });
}