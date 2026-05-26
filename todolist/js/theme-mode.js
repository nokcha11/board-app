document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("themeToggle");

  function applyTheme(theme) {
    const headerLight = document.getElementById("header-light-theme");
    const mainLight = document.getElementById("main-light-theme");
    const headerDark = document.getElementById("header-dark-theme");
    const mainDark = document.getElementById("main-dark-theme");

    const isDark = theme === "dark";

    const toggleText = document.querySelector(".toggle-text");

    if (toggleText) {
      toggleText.textContent = isDark ? "LIGHT" : "DARK";
    }

    if (headerLight) headerLight.disabled = isDark;
    if (mainLight) mainLight.disabled = isDark;
    if (headerDark) headerDark.disabled = !isDark;
    if (mainDark) mainDark.disabled = !isDark;

    document.body.classList.remove("light-mode", "dark-mode");
    document.body.classList.add(theme + "-mode");

    localStorage.setItem("todo-theme", theme);

    if (toggle) {
      toggle.classList.toggle("active", isDark);
      toggle.setAttribute("aria-pressed", isDark ? "true" : "false");
    }
  }
  
  const toggleText =
  document.querySelector(".toggle-text");

  const savedTheme = localStorage.getItem("todo-theme") || "light";
  applyTheme(savedTheme);

  if (toggle) {
    toggle.addEventListener("click", function () {
      const isDark = document.body.classList.contains("dark-mode");
      applyTheme(isDark ? "light" : "dark");
    });
  }
});