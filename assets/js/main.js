document.addEventListener("DOMContentLoaded", () => {
  const titleEl = document.getElementById("dynamic-title");
  const subtitleEl = document.getElementById("dynamic-subtitle");
  const slides = Array.isArray(window.dataSlides) ? window.dataSlides : [];

  if (!titleEl || !subtitleEl || slides.length <= 1) {
    return;
  }

  let currentIndex = 0;
  const intervalMs = 5000;
  const fadeMs = 600;

  const swapText = () => {
    titleEl.classList.remove("animate__fadeInUp");
    titleEl.classList.add("animate__fadeOutDown");
    subtitleEl.classList.remove("animate__fadeInUp");
    subtitleEl.classList.add("animate__fadeOutDown");

    setTimeout(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      const slide = slides[currentIndex] || {};

      titleEl.textContent = slide.judul || "";
      subtitleEl.textContent = slide.subjudul || "";

      titleEl.classList.remove("animate__fadeOutDown");
      titleEl.classList.add("animate__fadeInUp");
      subtitleEl.classList.remove("animate__fadeOutDown");
      subtitleEl.classList.add("animate__fadeInUp");
    }, fadeMs);
  };

  setInterval(swapText, intervalMs);
});
