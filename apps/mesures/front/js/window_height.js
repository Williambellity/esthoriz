$(document).ready(function() {
  function setWindowHeight() {
    windowHeight = $(window).innerHeight();
    $('.item').css('min-height', windowHeight);
	$('.carousel').css('min-height', windowHeight);
  };
  setWindowHeight();
  
  $(window).resize(function() {
    setWindowHeight();
  });
});