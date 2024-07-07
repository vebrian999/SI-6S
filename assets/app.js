window.addEventListener("scroll", function () {
  var navbar = document.querySelector(".navbar");
  var scrollTrigger = 300; // Atur nilai threshold di sini (misalnya 100px)
  if (window.scrollY > scrollTrigger) {
    navbar.classList.add("navbar-scrolled");
  } else {
    navbar.classList.remove("navbar-scrolled");
  }
});

document.getElementById("dropdownDefaultButton").addEventListener("click", function () {
  var dropdown = document.getElementById("dropdown");
  dropdown.classList.toggle("hidden");
});
