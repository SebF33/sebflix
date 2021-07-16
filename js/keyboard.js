// Navigation du carrousel au clavier
$("html body").keydown(function (e) {
  // Touche "Entrée"
  if (e.keyCode == 13) {
    var href = $(".active").children("a").attr("href");
    window.location.href = href;
    // Touche "Flêche de gauche"
  } else if (e.keyCode == 37) {
    $(".active").prev().trigger("click");
    // Touche "Flêche de droite"
  } else if (e.keyCode == 39) {
    $(".active").next().trigger("click");
  }
});

// Prévention des bugs d'affichage
$(".random-carousel-btn").click(function () {
  $(".random-carousel-btn").css("pointer-events", "none");
  setTimeout(function () {
    $(".random-carousel-btn").css("pointer-events", "auto");
  }, 600);
});
$("html body").keydown(function (e) {
  if (e.keyCode === 37 || e.keyCode === 39) {
    $(".random-carousel-btn").css("pointer-events", "none");
  }
});
$("html body").keypress(function (e) {
  if (e.keyCode === 37 || e.keyCode === 39) {
    $(".random-carousel-btn").css("pointer-events", "none");
  }
});
$("html body").keyup(function (e) {
  if (e.keyCode === 37 || e.keyCode === 39) {
    $(".random-carousel-btn").css("pointer-events", "none");
    setTimeout(function () {
      $(".random-carousel-btn").css("pointer-events", "auto");
    }, 600);
  }
});
