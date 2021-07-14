$num = $(".my-card").length;
$even = $num / 2;
$odd = ($num + 1) / 2;

if ($num % 2 == 0) {
  $(".my-card:nth-child(" + $even + ")").addClass("active");
  $(".my-card:nth-child(" + $even + ")")
    .prev()
    .addClass("prev");
  $(".my-card:nth-child(" + $even + ")")
    .next()
    .addClass("next");
} else {
  $(".my-card:nth-child(" + $odd + ")").addClass("active");
  $(".my-card:nth-child(" + $odd + ")")
    .prev()
    .addClass("prev");
  $(".my-card:nth-child(" + $odd + ")")
    .next()
    .addClass("next");
}

$(".my-card").click(function () {
  $slide = $(".active").width();
  console.log($(".active").position().left);

  if ($(this).hasClass("next")) {
    $(".card-carousel")
      .stop(false, true)
      .animate({
        left: "-=" + $slide,
      });
  } else if ($(this).hasClass("prev")) {
    $(".card-carousel")
      .stop(false, true)
      .animate({
        left: "+=" + $slide,
      });
  }
  $(this).removeClass("prev next");
  $(this).siblings().removeClass("prev active next");
  $(this).addClass("active");
  $(this).prev().addClass("prev");
  $(this).next().addClass("next");
});

$("a").click(function (e) {
  if ($(this).parent().hasClass("next")) {
    e.preventDefault();
  }
  if ($(this).parent().hasClass("prev")) {
    e.preventDefault();
  }
});

// Navigation au clavier
$("html body").keydown(function (e) {
  if (e.keyCode == 37) {
    // Touche "Flêche de gauche"
    $(".active").prev().trigger("click");
  } else if (e.keyCode == 39) {
    // Touche "Flêche de droite"
    $(".active").next().trigger("click");
  }
});
