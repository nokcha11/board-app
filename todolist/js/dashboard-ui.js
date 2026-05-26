document.addEventListener("DOMContentLoaded", function () {
  function updateClock() {
    const now = new Date();
    const hour = String(now.getHours()).padStart(2, "0");
    const min = String(now.getMinutes()).padStart(2, "0");

    const clock = document.getElementById("clockText");

    if (clock) {
      clock.innerText = hour + ":" + min;
    }
  }

  updateClock();
  setInterval(updateClock, 1000);

  const tabs = document.querySelectorAll(".navigator-tabs button[data-target]");

  tabs.forEach(function (tab) {
    tab.addEventListener("click", function () {
      const target = document.getElementById(tab.dataset.target);
      const message = document.getElementById("navigatorMessage");

      tabs.forEach(function (item) {
        item.classList.remove("active");
      });

      tab.classList.add("active");

      if (message) {
        message.innerText = tab.dataset.message || "";
      }

      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    });
  });
});