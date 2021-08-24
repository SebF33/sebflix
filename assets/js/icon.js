// Icône Sebflix
$("#icon-trigger").click(function () {
  $("li").toggleClass("icon-visible");
});

// Suppression du lien de l'icône en responsive
var checkSize = function () {
  var minSize = $(window).width();
  if (minSize <= 1223) {
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
