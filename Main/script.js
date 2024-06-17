
var slideIndex = 1;
showSlides(slideIndex);


function plusSlides(n) {
  showSlides(slideIndex += n);
}


function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var slides = document.getElementsByClassName("slide");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  
  
  for (var i = 0; i < slides.length; i++) {
      slides[i].className = slides[i].className.replace(" active", "");
  }

  
  slides[slideIndex-1].className += " active";
}


window.addEventListener('scroll', function() {
  var navbar = document.querySelector('.header');
  if (window.scrollY === 0) {
      navbar.style.backgroundColor = 'transparent';
  } else {
      navbar.style.backgroundColor = '#071430';
  }
});


document.querySelector('.slider_arrow-left').addEventListener('click', function() {
  plusSlides(1);
});

document.querySelector('.slider_arrow-right').addEventListener('click', function() {
  plusSlides(-1);
});

/*Открытие модальных окон*/
function openModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = 'flex'; 
  modal.style.animation = 'none';
  setTimeout(() => {
    modal.style.animation = '';
  }, 10);
}

function closeModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = 'none';
}

window.onclick = function(event) {
  if (event.target.className === 'modal') {
    event.target.style.display = 'none';
  }
}

function redirectToCalculator() {
  window.location.href = '..\\Calculator\\index.php'; 
}

/*Burger*/
function toggleMenu() {
  var links = document.querySelector('.nav-links');
  links.classList.toggle('show');
}
