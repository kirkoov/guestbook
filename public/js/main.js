// https://stackoverflow.com/questions/4528085/toggle-show-hide-div-with-button#4528100

var toggle  = document.getElementById("info");
var content = document.getElementById("intro");

toggle.addEventListener("click", function() {
  content.style.display = (content.dataset.toggled ^= 1) ? "block" : "none";
});