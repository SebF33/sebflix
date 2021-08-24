// Discart
var el = document.getElementById("discart-img");
var tl = new TimelineMax({
  paused: true,
});

tl.set(el, {
  willChange: "transform",
}).to(el, 1, {
  transform: "rotate(60deg)",
  ease: Power1.easeInOut,
});

el.animation = tl;

$(el)
  .on("mouseenter", function () {
    this.animation.play();
  })
  .on("mouseleave", function () {
    this.animation.reverse();
  });
