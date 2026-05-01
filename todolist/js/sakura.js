/* ================= 벚꽃 생성 효과 ================= */
function createPetal() {
  const petal = document.createElement("div");
  petal.className = "petal";

  /* 꽃잎 시작 위치 랜덤 */
  petal.style.left = Math.random() * window.innerWidth + "px";

  /* 꽃잎 크기 랜덤 */
  const size = 12 + Math.random() * 12;
  petal.style.setProperty("--size", size + "px");

  /* 떨어지는 속도 랜덤 */
  const duration = 7 + Math.random() * 6;
  petal.style.setProperty("--duration", duration + "s");

  /* 투명도 랜덤 */
  const opacity = 0.45 + Math.random() * 0.35;
  petal.style.setProperty("--opacity", opacity);

  /* 시작 회전 랜덤 */
  const startRotate = Math.random() * 360;
  petal.style.setProperty("--start-rotate", startRotate + "deg");

  /* 바람 방향/세기 랜덤 */
  const wind = (Math.random() * 260 - 130);
  petal.style.setProperty("--wind", wind + "px");

  document.body.appendChild(petal);

  setTimeout(() => {
    petal.remove();
  }, duration * 1000);
}

/* ================= 꽃잎 생성 간격 =================
   숫자가 작을수록 꽃잎이 많이 나옴
   추천: 350~600
*/
setInterval(createPetal, 450);