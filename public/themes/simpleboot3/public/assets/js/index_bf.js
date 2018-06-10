$(function () {
  var $searchNav = $('#searchNav');
  $searchNav.on('click','a',function () {
    $(this).addClass('color-red').parents().siblings().find('a').removeClass('color-red')
  })
});