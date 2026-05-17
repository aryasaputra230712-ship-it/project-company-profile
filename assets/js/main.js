const titleEl = document.getElementById("dynamic-title");
const subtitleEl = document.getElementById("dynamic-subtitle");
const dataSlides = window.dataSlides || [];
let index = 0;

function gantiTeks() {
  if (!dataSlides.length) return;

  titleEl.classList.add("fade-text");
  subtitleEl.classList.add("fade-text");

  setTimeout(() => {
    index = (index + 1) % dataSlides.length;
    titleEl.textContent = dataSlides[index].title;
    subtitleEl.textContent = dataSlides[index].subtitle;

    titleEl.classList.remove("fade-text");
    subtitleEl.classList.remove("fade-text");
  }, 400);
}

if (dataSlides.length > 1) {
  setInterval(gantiTeks, 4000);
}
