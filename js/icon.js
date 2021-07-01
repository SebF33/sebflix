$("#icon-trigger").click(function () {
  $("li").toggleClass("icon-visible");
});

// Supprimer le lien de l'icône en responsive
var checkMinSize = function () {
  var minSize = $(window).width();
  if (minSize <= 1660) {
    $(".btn-icon").removeAttr("href");
  } else {
    $(".btn-icon").attr("href");
  }
};

var checkMaxSize = function () {
  var maxSize = $(window).width();
  if (maxSize > 1660) {
    $(".btn-icon").attr("href");
  } else {
    $(".btn-icon").removeAttr("href");
  }
};

$(document).ready(function () {
  checkMinSize();
  checkMaxSize();
});

$(window).resize(function () {
  checkMinSize();
  checkMaxSize();
});
