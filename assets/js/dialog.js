// Boîte de dialogue
$(function () {
  $(".btn-show-modal").click(function (e) {
    e.preventDefault();
    var id = $(this).attr("id");
    var modal_id = "dialog-example_" + id;
    $("#" + modal_id).modal("show");
  });
  $(".btn btn-danger").click(function (e) {
    var id = $(this).attr("id");
    var modal_id = "dialog-example_" + id;
    $("#" + modal_id).modal("hide");
  });
});
