const month = typeof calendarMonth !== "undefined"
  ? calendarMonth
  : (new Date().getMonth() + 1);

let season = "";

if (month >= 3 && month <= 5) {
  season = "spring";
} else if (month >= 6 && month <= 8) {
  season = "summer";
} else if (month >= 9 && month <= 11) {
  season = "autumn";
} else {
  season = "winter";
}

document.body.setAttribute("data-season", season);

function createSplash(x, y) {
  for (let i = 0; i < 9; i++) {
    const dot = document.createElement("div");
    dot.className = "splash-dot";

    const angle = Math.random() * Math.PI * 2;
    const distance = 18 + Math.random() * 34;

    dot.style.left = x + "px";
    dot.style.top = y + "px";
    dot.style.setProperty("--dot-size", (3 + Math.random() * 5) + "px");
    dot.style.setProperty("--sx", Math.cos(angle) * distance + "px");
    dot.style.setProperty("--sy", Math.sin(angle) * distance + "px");

    document.body.appendChild(dot);

    setTimeout(() => {
      dot.remove();
    }, 450);
  }
}

function popBubble(bubble) {
  if (!bubble || bubble.classList.contains("pop")) return;

  const rect = bubble.getBoundingClientRect();
  const x = rect.left + rect.width / 2;
  const y = rect.top + rect.height / 2;

  bubble.classList.add("pop");
  createSplash(x, y);

  setTimeout(() => {
    bubble.remove();
  }, 350);
}

function createSeasonParticle() {
  const particle = document.createElement("div");
  particle.classList.add("season-particle");

  if (season === "spring") {
    particle.classList.add("petal");

  } else if (season === "summer") {
    particle.classList.add("rain");

  } else if (season === "autumn") {
    particle.classList.add("leaf");

    const random = Math.random();

    if (random < 0.33) {
      particle.classList.add("leaf-red");
    } else if (random < 0.66) {
      particle.classList.add("leaf-yellow");
    } else {
      particle.classList.add("leaf-green");
    }

  } else {
    particle.classList.add("snow");
  }

  particle.style.left = Math.random() * window.innerWidth + "px";

  let size = 20;

  if (season === "summer") {
    if (Math.random() < 0.72) {
      size = 42 + Math.random() * 48;
    } else {
      size = 95 + Math.random() * 95;
    }

  } else if (season === "winter") {
    size = 45 + Math.random() * 55;

  } else if (season === "spring") {
    size = 12 + Math.random() * 14;

  } else {
    size = 14 + Math.random() * 10;
  }

  particle.style.setProperty("--size", size + "px");

  let duration = 7 + Math.random() * 6;

  if (season === "summer") {
    duration = 6 + Math.random() * 4;
  } else if (season === "winter") {
    duration = 7 + Math.random() * 5;
  } else if (season === "autumn") {
    duration = 8 + Math.random() * 6;
  }

  particle.style.setProperty("--duration", duration + "s");
  particle.style.setProperty("--opacity", 0.5 + Math.random() * 0.3);
  particle.style.setProperty("--start-rotate", Math.random() * 360 + "deg");
  particle.style.setProperty("--wind", (Math.random() * 240 - 120) + "px");

  document.body.appendChild(particle);

  if (season === "summer") {
    particle.addEventListener("mouseenter", function () {
      popBubble(particle);
    });
  }

  setTimeout(() => {
    if (season === "spring") {
      particle.style.top = "calc(100vh - 20px)";
      particle.style.position = "fixed";
      particle.style.opacity = "0.8";
      particle.style.transform = "rotate(" + (Math.random() * 360) + "deg)";
    } else {
      particle.remove();
    }
  }, duration * 1000);
}

let intervalTime = 700;

if (season === "summer") {
  intervalTime = 750;
} else if (season === "winter") {
  intervalTime = 450;
} else if (season === "autumn") {
  intervalTime = 620;
} else {
  intervalTime = 700;
}

setInterval(createSeasonParticle, intervalTime);

document.addEventListener("mousemove", function(e) {
  if (Math.random() > 0.88) {
    const particle = document.createElement("div");

    if (season === "summer") {
      particle.className = "season-particle rain mouse-bubble";

      const size = 10 + Math.random() * 12;
      particle.style.setProperty("--size", size + "px");
      particle.style.setProperty("--duration", "1.4s");
      particle.style.setProperty("--opacity", 0.65 + Math.random() * 0.25);
      particle.style.setProperty("--wind", (Math.random() * 80 - 40) + "px");

    } else if (season === "winter") {
      particle.className = "season-particle snow mouse-snow";

      const size = 14 + Math.random() * 16;
      particle.style.setProperty("--size", size + "px");
      particle.style.setProperty("--duration", "1.5s");
      particle.style.setProperty("--opacity", 0.55 + Math.random() * 0.3);
      particle.style.setProperty("--wind", (Math.random() * 80 - 40) + "px");

    } else {
      particle.className = "season-particle petal mouse-petal";

      const size = 10 + Math.random() * 10;
      particle.style.setProperty("--size", size + "px");
      particle.style.setProperty("--duration", "1.6s");
      particle.style.setProperty("--opacity", 0.45 + Math.random() * 0.25);
      particle.style.setProperty("--start-rotate", Math.random() * 360 + "deg");
      particle.style.setProperty("--wind", (Math.random() * 90 - 45) + "px");
    }

    particle.style.left = e.clientX + "px";
    particle.style.top = e.clientY + "px";

    document.body.appendChild(particle);

    setTimeout(() => {
      particle.remove();
    }, 1600);
  }
});