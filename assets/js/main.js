document.addEventListener("DOMContentLoaded", () => {
  // --- LOGIKA SLIDER (Sudah Oke) ---
  const titleEl = document.getElementById("dynamic-title");
  const subtitleEl = document.getElementById("dynamic-subtitle");
  const slides = window.dataSlides;
  let currentIndex = 0;

  if (slides && slides.length > 1) {
    setInterval(() => {
      titleEl.classList.remove("animate__fadeInUp");
      titleEl.classList.add("animate__fadeOutDown");
      subtitleEl.classList.remove("animate__fadeInUp");
      subtitleEl.classList.add("animate__fadeOutDown");

      setTimeout(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        titleEl.textContent = slides[currentIndex].judul;
        subtitleEl.textContent = slides[currentIndex].subjudul;

        titleEl.classList.remove("animate__fadeOutDown");
        titleEl.classList.add("animate__fadeInUp");
        subtitleEl.classList.remove("animate__fadeOutDown");
        subtitleEl.classList.add("animate__fadeInUp");
      }, 600);
    }, 5000);
  }
});
