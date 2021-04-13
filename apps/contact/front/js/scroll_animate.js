var $animation_elmts = $('.animation-element');
var $w = $(window);

function scroll_animate() {
  var w_height = $w.height();
  var w_top_position = $w.scrollTop();
  var w_bottom_position = (w_top_position + w_height);

  $.each($animation_elmts, function() {
    var $elmts = $(this);
    var elmts_height = $elmts.outerHeight();
    var elmts_top_position = $elmts.offset().top;
    var elmts_bottom_position = (elmts_top_position + elmts_height);

    if ((elmts_bottom_position >= w_top_position) &&
        (elmts_top_position <= w_bottom_position)) {
      $elmts.addClass('animated');
    }
  });
}

$w.on('scroll resize', scroll_animate);
$w.trigger('scroll');