(() => {
  'use strict';
  const area = document.getElementById('color-area');
  const grid = document.getElementById('color-grid');
  const notice = document.getElementById('color-notice');
  let selectedColor = '#f7f9fc';
  let hideTimer;
  grid.addEventListener('mouseover', e => {
    const cell = e.target.closest('.color-cell');
    if (cell) area.style.backgroundColor = cell.dataset.color;
  });
  grid.addEventListener('mouseleave', () => { area.style.backgroundColor = selectedColor; });
  grid.addEventListener('click', e => {
    const cell = e.target.closest('.color-cell');
    if (!cell) return;
    selectedColor = cell.dataset.color;
    area.style.backgroundColor = selectedColor;
    notice.textContent = `Đã chọn màu ${selectedColor}`;
    notice.classList.add('visible');
    clearTimeout(hideTimer);
    hideTimer = setTimeout(() => notice.classList.remove('visible'), 2600);
  });
})();
