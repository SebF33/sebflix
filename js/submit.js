function submitForm() {
  if (document.formRadios.radio[0].checked == true) {
    var type = "movies";
  }
  if (document.formRadios.radio[1].checked == true) {
    var type = "tvshows";
  }
  if (document.formRadios.radio[2].checked == true) {
    var type = "actors";
  }
  if (document.formRadios.radio[3].checked == true) {
    var type = "studios";
  }
  var search = document.getElementById("searchInput").value;
  var url = "src/views/display-results.php?type=" + type + "&search=" + search;
  document.forms.formText.action = url;
}
