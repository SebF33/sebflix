function submitForm() {
  if (document.formRadios.radio[0].checked == true) {
    var id = "movies";
  }
  if (document.formRadios.radio[1].checked == true) {
    var id = "tvshows";
  }
  if (document.formRadios.radio[2].checked == true) {
    var id = "actors";
  }
  if (document.formRadios.radio[3].checked == true) {
    var id = "studios";
  }
  var search = document.getElementById("searchInput").value;
  var url = "php/display-search.php?id=" + id + "&search=" + search;
  document.forms.formText.action = url;
}
