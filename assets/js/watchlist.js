// Watchlist
function addToWatchlist(obj) {
  var id = $(obj).data("id");
  var type = $(obj).data("type");
  var user = $(obj).data("user");
  $(obj).find("#watch-off").hide();
  $.ajax({
    url: "watchlist/add-to-watchlist.php",
    type: "POST",
    data: { id: id, type: type, user: user },
    success: function (data) {
      setTimeout(function () {
        $(obj).find("#watch-on").show();
        markedAsChecked($(obj));
      }, 50);
    },
  });
}

function removeFromWatchlist(obj) {
  var id = $(obj).data("id");
  var type = $(obj).data("type");
  var user = $(obj).data("user");
  $(obj).find("#watch-on").hide();
  $.ajax({
    url: "watchlist/remove-from-watchlist.php",
    type: "POST",
    data: { id: id, type: type, user: user },
    success: function (data) {
      setTimeout(function () {
        $(obj).find("#watch-off").show();
        markedAsUnchecked($(obj));
      }, 50);
    },
  });
}

function markedAsChecked(obj) {
  $(obj).find("svg").parent().attr("onClick", "removeFromWatchlist(this)");
  $(obj).find("svg").parent().attr("title", "Retirer de la watchlist");
}

function markedAsUnchecked(obj) {
  $(obj).find("svg").parent().attr("onClick", "addToWatchlist(this)");
  $(obj).find("svg").parent().attr("title", "Ajouter à la watchlist");
}
