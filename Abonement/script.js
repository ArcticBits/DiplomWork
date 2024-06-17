
/*Burger*/
function toggleMenu() {
  var links = document.querySelector('.nav-links');
  links.classList.toggle('show');
}


document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll('.count');
  const speed = 200; 
  const contactText = document.querySelector('.contact_text');

  // Функция для анимации счета чисел
  const animateCount = () => {
    counters.forEach(counter => {
      const updateCount = () => {
        const target = +counter.getAttribute('data-count');
        const count = +counter.innerText;

        const increment = target / speed;

        if (count < target) {
          counter.innerText = Math.ceil(count + increment);
          setTimeout(updateCount, 10);
        } else {
          counter.innerText = target;
        }
      };

      updateCount();
    });
  };

  // Функция для анимации появления элементов
  const animateVisibility = () => {
    const pElements = contactText.querySelectorAll('p');
    pElements.forEach((p, index) => {
      setTimeout(() => {
        p.classList.add('visible');
      }, index * 200); 
    });
  };

  // Функция для проверки видимости элемента
  const isInViewport = (elem) => {
    const bounding = elem.getBoundingClientRect();
    return (
      bounding.top >= 0 &&
      bounding.left >= 0 &&
      bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  };

  // Функция для запуска анимации при прокрутке
  const onScroll = () => {
    if (isInViewport(contactText)) {
      animateVisibility();
      animateCount();
      window.removeEventListener('scroll', onScroll);
    }
  };

  window.addEventListener('scroll', onScroll);
});

