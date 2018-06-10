$(function () {
  // 倒计时
  ~(function () {
    var timer = null;
    var $countDown = $('#countDown');

    function addZero(val) {
      return val < 10 ? "0" + val : val;
    }

    function computerTime() {
      var resStr = "00:00:00";
      var tarTime = new Date("2018/10/29 00:04:00");
      var nowTime = new Date();
      var spanTime = tarTime.getTime() - nowTime.getTime();
      if (spanTime > 0) {
        var day = Math.floor(spanTime / (1000 * 60 * 60 * 24));
        spanTime = spanTime - (day * 1000 * 60 * 60 * 24);
        var hour = Math.floor(spanTime / (1000 * 60 * 60));
        spanTime = spanTime - hour * 1000 * 60 * 60;
        var minuter = Math.floor(spanTime / (1000 * 60));
        spanTime = spanTime - minuter * 60 * 1000;
        var second = Math.floor(spanTime / 1000);
        resStr = day + '天' + addZero(hour) + ":" + addZero(minuter) + ":" + addZero(second);

      } else {
        window.clearInterval(timer);
        timer = null;
      }
      return resStr;
    }

    $countDown.text(computerTime());
    timer = window.setInterval(function () {
      $countDown.text(computerTime());
    }, 1000)
  })();
  // 图片轮播
  ~(function () {
    var $picSmallList = $('#picSmallList');
    var $picSmall = $('#picSmall');
    var $picBig = $('#picBig');
    var className = 'selected';
    var curShowSize = 5;
    var cloneEle = $picSmallList.find('li:lt(' + curShowSize + ')').clone();
    $picSmallList.append(cloneEle);
    var picSmallWidth = $picSmallList.find('li').outerWidth(true);
    var picSmallSize = $picSmallList.find('li').size();
    $picSmallList.width(picSmallWidth * picSmallSize);
    $picSmallList.find('li').first().addClass(className);
    // 切换大图
    $picSmallList.on('click', 'li', function () {
      $(this).addClass(className).siblings().removeClass(className);
      var curImgUrl = $(this).find('img').attr('src');
      $picBig.find('img').attr('src', curImgUrl);
    });
    var clickCount = 0;

    function moveL() {
      clickCount++;
      if (clickCount === picSmallSize - cloneEle.size()) {
        $picSmallList.css('left', clickCount);
        clickCount = 1;
      }
    }

    function moveR() {
      clickCount--;
      if (clickCount === -1) {
        $picSmallList.css('left', -(picSmallSize - curShowSize - 1) * picSmallWidth);
        clickCount = picSmallSize - curShowSize - 2;
      }
    }

    $picSmall.on('click', '.btn', function () {
      $(this).hasClass('next') ? moveL() : moveR();
      $picSmallList.stop().animate({left: -clickCount * picSmallWidth}, 600);
    })
  })();
  // 内容切换
  ~(function(){
    var $homeImg = $('#homeImg');
    var $content = $('#content');
    $content.find('.content-item').first().addClass('selected');
  /*   $homeImg.find('span').hover(function(){

      $content.find('.content-item').eq($(this).index()).addClass('selected')
    }, function(){
      console.log('likai');
      console.log($(this).index())
      $content.find('.content-item').eq($(this).index()).removeClass('selected')
    }) */
     $homeImg.on('mouseover','span', function(){
      var curIndex = $(this).index();
      console.log(curIndex);
      $content.find('.content-item').eq(curIndex).addClass('selected').siblings().removeClass('selected')
    }) 
  })()
});