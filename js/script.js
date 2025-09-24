// В script.js

// Функция для инициализации слайдера с настройками
function initSlider() {
  jQuery('.your-slider-class').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 10000, // 10 секунд
    arrows: false,
    dots: false,
    pauseOnHover: true,
    swipe: true,
    swipeToSlide: true,
  });

  // Подстраиваем высоту слайдера под самый высокий слайд
  function setSliderHeight() {
    var maxSlideHeight = 0;

    jQuery('.your-slider-class .slider-item').each(function() {
      var slideHeight = jQuery(this).outerHeight();
      if (slideHeight > maxSlideHeight) {
        maxSlideHeight = slideHeight;
      }
    });

    jQuery('.your-slider-class').css('height', maxSlideHeight + 'px');
  }

  // Вызываем функцию подстройки высоты слайдера при загрузке страницы и при изменении размера окна
  setSliderHeight();
  jQuery(window).on('resize', function() {
    setSliderHeight();
  });
}

// Ждем, пока страница полностью загрузится, и вызываем функцию инициализации слайдера
jQuery(document).ready(function() {
  initSlider();
});
