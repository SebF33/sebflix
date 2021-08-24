// Footer
var logoElement = $("footer .logo");
var logoSocial = $("footer .social");
var logoDev = $("footer .dev");

$(window).scroll(function () {
  if ($(window).scrollTop() + $(window).height() > $(document).height() - 80) {
    $(logoElement).addClass("show");
    $(logoSocial).addClass("show");
    $(logoDev).addClass("show");
  } else if (
    $(logoElement).hasClass("show") &&
    $(window).scrollTop() + $(window).height() > $(document).height() - 130
  ) {
    $(logoElement).removeClass("show");
    $(logoSocial).removeClass("show");
    $(logoDev).removeClass("show");
  }
});
