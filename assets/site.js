(function () {
  'use strict';
  // Bootstrap 4 layout with dependency-free menu and carousel controls.
  document.querySelectorAll('[data-navbar-toggle]').forEach(function (button) {
    const target = document.getElementById(button.getAttribute('aria-controls'));
    if (!target) return;
    button.addEventListener('click', function () {
      const isOpen = target.classList.toggle('show');
      button.setAttribute('aria-expanded', String(isOpen));
    });
  });

  document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
    const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
    const indicators = Array.from(carousel.querySelectorAll('[data-slide-to]'));
    if (slides.length === 0) return;
    let position = 0;
    function show(index) {
      position = (index + slides.length) % slides.length;
      slides.forEach(function (slide, i) {
        const isSelected = i === position;
        slide.classList.toggle('active', isSelected);
        slide.setAttribute('aria-hidden', String(!isSelected));
      });
      indicators.forEach(function (button, i) {
        button.classList.toggle('active', i === position);
        button.setAttribute('aria-label', 'Show slide ' + (i + 1));
        button.setAttribute('aria-current', i === position ? 'true' : 'false');
      });
    }
    carousel.querySelectorAll('[data-slide-direction]').forEach(function (button) {
      button.addEventListener('click', function () {
        show(position + (button.getAttribute('data-slide-direction') === 'next' ? 1 : -1));
      });
    });
    indicators.forEach(function (button) {
      button.addEventListener('click', function () { show(Number(button.dataset.slideTo)); });
    });
    carousel.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
        show(position + (event.key === 'ArrowRight' ? 1 : -1));
      }
    });
    show(0);
  });
}());
