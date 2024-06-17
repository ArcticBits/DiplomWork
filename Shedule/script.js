$(document).ready(function() {
    moment.locale('ru'); 
  
    $('#calendar').fullCalendar({
        locale: 'ru',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaDay'
        },
        buttonText: {
            today: 'Сегодня',
            month: 'Месяц',
            day: 'День'
        },
        events: 'fetch_events.php',
        eventClick: function(event) {

            $.ajax({
                url: 'fetch_workout_details.php',
                type: 'POST',
                data: { workout_id: event.id },
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#workoutTitle').text(data.title);
                    $('#trainerName').text(data.trainer_name);
                    $('#workoutDescription').text(data.description);
                    // Форматирование дату и время
                    const startDate = moment(data.start_time).format('D MMMM, HH:mm');
                    const endDate = moment(data.end_time).format('D MMMM, HH:mm');
                    $('#workoutTime').text(`${startDate} - ${endDate}`);
                    $('#capacity').text(data.capacity); 
                    $('#workout_id').val(data.id); 
                    $('#bookWorkoutBtn').show();
                    $('#bookingModal').show();
                }
            });
        },
        windowResize: function(view) {
            if (window.innerWidth < 768) {
                $('#calendar').fullCalendar('changeView', 'agendaDay');
            } else {
                $('#calendar').fullCalendar('changeView', 'month');
            }
        }
    });

    $('.close').on('click', function() {
        $('#bookingModal').hide();
    });

    $(document).mouseup(function(e) {
        var modal = $("#bookingModal");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            modal.hide();
        }
    });
  
    // Обработка записи на тренировку
    $('#bookWorkoutBtn').on('click', function() {
        const workout_id = $('#workout_id').val();
        $.ajax({
            url: 'book_workout.php',
            type: 'POST',
            data: { workout_id: workout_id },
            success: function(response) {
                alert(response);
                if (response === "Вы успешно записались на тренировку!") {
                    $('#bookingModal').hide();
                }
            }
        });
    });
});
function toggleMenu() {
    var links = document.querySelector('.nav-links');
    links.classList.toggle('show');
  }
  