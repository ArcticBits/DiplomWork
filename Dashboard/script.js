/*Burger*/
function toggleMenu() {
  var links = document.querySelector('.nav-links');
  links.classList.toggle('show');
}
function openModal(workoutId) {
  document.getElementById('cancel_workout_id').value = workoutId;
  document.getElementById('confirmationModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('confirmationModal').style.display = 'none';
}

window.onclick = function(event) {
  if (event.target == document.getElementById('confirmationModal')) {
      closeModal();
  }
}


