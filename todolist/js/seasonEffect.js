/* ================= 계절 자동 선택 =================
   ToDo_list.php에서는 calendarMonth 값을 사용
   index.php에서는 실제 현재 월을 사용
*/
const month = typeof calendarMonth !== "undefined"
  ? calendarMonth
  : (new Date().getMonth() + 1);

let season = "";

if (month >= 3 && month <= 5) {
  season = "spring"; // 봄: 벚꽃
} else if (month >= 6 && month <= 8) {
  season = "summer"; // 여름: 비
} else if (month >= 9 && month <= 11) {
  season = "autumn"; // 가을: 낙엽
} else {
  season = "winter"; // 겨울: 눈
}

document.body.setAttribute("data-season", season);

/* 계절별 색상 변경 */
if (season === "spring") {
  document.body.style.background = "linear-gradient(135deg,#ffe4ec,#ffcdd2)";
}
if (season === "summer") {
  document.body.style.background = "linear-gradient(135deg,#d6f0ff,#aee1ff)";
}
if (season === "autumn") {
  document.body.style.background = "linear-gradient(135deg,#ffe0c2,#ffb74d)";
}
if (season === "winter") {
  document.body.style.background = "linear-gradient(135deg,#eef3ff,#cfd8ff)";
}

/* ================= 계절별 파티클 생성 ================= */
function createSeasonParticle() {
  const particle = document.createElement("div");
  particle.classList.add("season-particle");

  if (season === "spring") {
    particle.classList.add("petal");
  } else if (season === "summer") {
    particle.classList.add("rain");
  } else if (season === "autumn") {
    particle.classList.add("leaf");
  } else {
    particle.classList.add("snow");
  }

  particle.style.left = Math.random() * window.innerWidth + "px";

  const size = 10 + Math.random() * 12;
  particle.style.setProperty("--size", size + "px");

  const duration =
    season === "summer"
      ? 0.8 + Math.random() * 0.7
      : 7 + Math.random() * 6;

  particle.style.setProperty("--duration", duration + "s");

  const opacity = 0.35 + Math.random() * 0.35;
  particle.style.setProperty("--opacity", opacity);

  const startRotate = Math.random() * 360;
  particle.style.setProperty("--start-rotate", startRotate + "deg");

  const wind = Math.random() * 240 - 120;
  particle.style.setProperty("--wind", wind + "px");

  document.body.appendChild(particle);

  /* 쌓이지 않고 사라지게
  setTimeout(() => {
    particle.remove();
  }, duration * 1000);*/

  // 👉 쌓이게 변경
setTimeout(() => {
  if (season === "spring") {
    particle.style.top = "calc(100vh - 20px)";
    particle.style.position = "fixed";
    particle.style.opacity = "0.8";
    particle.style.transform = "rotate(" + (Math.random()*360) + "deg)";
  } else {
    particle.remove();
  }
}, duration * 1000);
}


/* ================= 계절별 생성 속도 ================= */
let intervalTime = 700;

if (season === "summer") {
  intervalTime = 180;
} else if (season === "winter") {
  intervalTime = 350;
} else {
  intervalTime = 700;
}

setInterval(createSeasonParticle, intervalTime);


/* ================= 마우스 꽃잎 효과 =================
   계절과 상관없이 마우스 주변에는 벚꽃잎이 흩날림
*/
document.addEventListener("mousemove", function(e) {
  if (Math.random() > 0.88) {
    const petal = document.createElement("div");

    petal.className = "season-particle petal mouse-petal";

    const size = 8 + Math.random() * 8;
    petal.style.setProperty("--size", size + "px");

    petal.style.setProperty("--duration", "1.6s");

    const opacity = 0.45 + Math.random() * 0.25;
    petal.style.setProperty("--opacity", opacity);

    petal.style.setProperty("--start-rotate", Math.random() * 360 + "deg");
    petal.style.setProperty("--wind", (Math.random() * 90 - 45) + "px");

    petal.style.left = e.clientX + "px";
    petal.style.top = e.clientY + "px";

    document.body.appendChild(petal);

    setTimeout(() => {
      petal.remove();
    }, 1600);
  }
});