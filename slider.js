(function() {
  // Получение всех слайдов
  var slides = document.querySelectorAll('.slider-item');
  
  // Индекс текущего слайда
  var currentSlide = 0;
  
  // Функция для отображения следующего слайда
  function showNextSlide() {
    // Скрытие текущего слайда
    slides[currentSlide].classList.remove('active');
    
    // Увеличение индекса текущего слайда
    currentSlide = (currentSlide + 1) % slides.length;
    
    // Отображение следующего слайда
    slides[currentSlide].classList.add('active');
  }
  
  // Установка интервала для автоматического переключения слайдов
  setInterval(showNextSlide, 5000); // Здесь 5000 - интервал времени между слайдами (в миллисекундах)
})();
