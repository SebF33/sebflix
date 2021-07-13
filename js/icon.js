// Menu mobile
$("#icon-trigger").click(function () {
  $("li").toggleClass("icon-visible");
});

// Supprimer le lien de l'icône en responsive
var checkSize = function () {
  var minSize = $(window).width();
  if (minSize <= 1023) {
    $(".btn-icon").removeAttr("href");
  } else {
    $(".btn-icon").attr("href", "/index.php");
  }
};
$(document).ready(function () {
  checkSize();
});
$(window).resize(function () {
  checkSize();
});
