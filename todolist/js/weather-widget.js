document.addEventListener("DOMContentLoaded", function () {
  const config = window.TODO_CONFIG || {};
  const OPENWEATHER_KEY = config.OPENWEATHER_KEY || "";
  const WEATHER_CITY = config.WEATHER_CITY || "Seoul";

  const weatherBox = document.getElementById("weatherBox");

  function setTimeTheme() {
    const hour = new Date().getHours();

    document.body.classList.remove(
      "time-dawn",
      "time-day",
      "time-evening",
      "time-night"
    );

    if (hour >= 5 && hour < 11) {
      document.body.classList.add("time-dawn");
    } else if (hour >= 11 && hour < 17) {
      document.body.classList.add("time-day");
    } else if (hour >= 17 && hour < 21) {
      document.body.classList.add("time-evening");
    } else {
      document.body.classList.add("time-night");
    }
  }

  function resetWeatherTheme() {
    document.body.classList.remove(
      "weather-clear",
      "weather-clouds",
      //"weather-rain",
      "weather-snow",
      "weather-thunder",
      "weather-mist"
    );

    const oldEffect = document.querySelector(".weather-effect-layer");
    if (oldEffect) oldEffect.remove();
  }

  function createWeatherParticles(type) {
    const layer = document.createElement("div");
    layer.className = "weather-effect-layer " + type;

    const count = type === "snow-effect" ? 42 : 34;

    for (let i = 0; i < count; i++) {
      const particle = document.createElement("span");
      particle.style.left = Math.random() * 100 + "%";
      particle.style.animationDelay = Math.random() * 4 + "s";
      particle.style.animationDuration = 3 + Math.random() * 5 + "s";
      particle.style.opacity = 0.35 + Math.random() * 0.55;
      layer.appendChild(particle);
    }

    document.body.appendChild(layer);
  }

  function applyWeatherTheme(main) {
    resetWeatherTheme();

    switch (main) {
      case "Clear":
        document.body.classList.add("weather-clear");
        break;
      case "Clouds":
        document.body.classList.add("weather-clouds");
        break;
      case "Rain":
      case "Drizzle":
        document.body.classList.add("weather-clear");
        break;
      case "Snow":
        document.body.classList.add("weather-clear");
        break;
      case "Thunderstorm":
        document.body.classList.add("weather-thunder");
        createWeatherParticles("rain-effect");
        break;
      case "Mist":
      case "Fog":
      case "Haze":
        document.body.classList.add("weather-mist");
        break;
      default:
        document.body.classList.add("weather-clouds");
    }
  }

  function getWeatherEmoji(main) {
    const icons = {
      Clear: "☀️",
      Clouds: "☁️",
      Rain: "🌧️",
      Drizzle: "🌦️",
      Snow: "❄️",
      Thunderstorm: "⛈️",
      Mist: "🌫️",
      Fog: "🌫️",
      Haze: "🌫️"
    };

    return icons[main] || "🌸";
  }

  setTimeTheme();

  if (!weatherBox) return;

  if (!OPENWEATHER_KEY) {
    weatherBox.innerHTML =
      "<p class='api-fallback'>API 키를 넣으면 날씨가 표시됩니다.</p>";
    return;
  }

  fetch(
    "https://api.openweathermap.org/data/2.5/weather?q=" +
      WEATHER_CITY +
      "&appid=" +
      OPENWEATHER_KEY +
      "&units=metric&lang=kr"
  )
    .then(function (res) {
      if (!res.ok) throw new Error("weather api error");
      return res.json();
    })
    .then(function (data) {
      const temp = Math.round(data.main.temp);
      const desc = data.weather[0].description;
      const main = data.weather[0].main;
      const icon = data.weather[0].icon;
      const emoji = getWeatherEmoji(main);

      applyWeatherTheme(main);

      weatherBox.innerHTML =
        "<div class='weather-current pretty-weather'>" +
          "<div class='weather-icon-wrap'>" +
            "<img src='https://openweathermap.org/img/wn/" + icon + "@2x.png' alt='" + desc + "'>" +
            "<b>" + emoji + "</b>" +
          "</div>" +
          "<div>" +
            "<span class='api-label'>" + WEATHER_CITY + " Weather</span>" +
            "<strong>" + temp + "°C</strong>" +
            "<p>" + desc + "</p>" +
          "</div>" +
        "</div>";
    })
    .catch(function () {
      weatherBox.innerHTML =
        "<p class='api-fallback'>날씨 정보를 불러올 수 없습니다.</p>";
    });
});