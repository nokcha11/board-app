setInterval(() => {
  const petal = document.createElement("div");
  petal.className = "petal";

  petal.style.width = (14 + Math.random() * 10) + "px";
  petal.style.height = (20 + Math.random() * 12) + "px";
  petal.style.left = Math.random() * window.innerWidth + "px";
  petal.style.animationDuration = (5 + Math.random() * 5) + "s";
  petal.style.transform = `rotate(${Math.random()*360}deg)`;

  document.body.appendChild(petal);

  setTimeout(() => {
    petal.remove();
  }, 10000);
}, 300);