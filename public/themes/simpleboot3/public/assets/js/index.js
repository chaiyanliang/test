$(function () {
  var $pointBox = $('#pointBox');
  var $carouselBox = $('#carouselBox');
  var selectedClass = 'selected';
  var num = 0;
  $pointBox.find('a').first().addClass(selectedClass);
  $pointBox.on('click','a', function () {
    num = $(this).index();
    $(this).addClass(selectedClass).siblings().removeClass(selectedClass);
    $carouselBox.find('.carousel-item').eq(num).fadeIn(600).siblings().hide();
  });
  function autoMove() {
    num++;
    if(num===$carouselBox.find('.carousel-item').length){
      num = 0
    }
    $pointBox.find('a').eq(num).addClass(selectedClass).siblings().removeClass(selectedClass);
    $carouselBox.find('.carousel-item').eq(num).fadeIn().siblings().hide();
  }
  var t1 = setInterval(function () {
    autoMove();
  },5000);
  $carouselBox.hover(function () {
    clearInterval(t1)
  },function () {
    t1 = setInterval(function () {
      autoMove()
    },5000)
  })
});