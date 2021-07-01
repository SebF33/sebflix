window.addEventListener("click", () => {
  if (document.formRadios.radio[0].checked == true) {
    document.getElementById("searchInput").placeholder = "Titre(s)";
  }
  if (document.formRadios.radio[1].checked == true) {
    document.getElementById("searchInput").placeholder = "Série(s)";
  }
  if (document.formRadios.radio[2].checked == true) {
    document.getElementById("searchInput").placeholder = "Acteur(s)";
  }
  if (document.formRadios.radio[3].checked == true) {
    document.getElementById("searchInput").placeholder = "Studio(s)";
  }
});
document.addEventListener("DOMContentLoaded", () => {
  document.formRadios.radio[0].checked = true;
});
