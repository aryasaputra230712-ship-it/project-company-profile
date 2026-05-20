document.addEventListener("DOMContentLoaded", () => {
  const titleEl = document.getElementById("dynamic-title");
  const subtitleEl = document.getElementById("dynamic-subtitle");
  const slides = window.dataSlides;
  let currentIndex = 0;

  if (slides && slides.length > 1) {
    setInterval(() => {
      // 1. Tambahkan animasi KELUAR
      // Kita pakai fadeOutDown agar teks seolah tenggelam
      titleEl.classList.remove("animate__fadeInUp");
      titleEl.classList.add("animate__fadeOutDown");

      subtitleEl.classList.remove("animate__fadeInUp");
      subtitleEl.classList.add("animate__fadeOutDown");

      // 2. Tunggu animasi keluar selesai (sekitar 500ms)
      setTimeout(() => {
        // Ganti Konten Teks
        currentIndex = (currentIndex + 1) % slides.length;
        titleEl.textContent = slides[currentIndex].judul;
        subtitleEl.textContent = slides[currentIndex].subjudul;

        // 3. Tambahkan animasi MASUK
        // Hilangkan class keluar, ganti dengan class masuk
        titleEl.classList.remove("animate__fadeOutDown");
        titleEl.classList.add("animate__fadeInUp");

        subtitleEl.classList.remove("animate__fadeOutDown");
        subtitleEl.classList.add("animate__fadeInUp");
      }, 600); // Jeda ini harus sinkron dengan durasi animate.css (default 1s, tapi fadeOut biasanya lebih cepat)
    }, 5000); // Ganti teks setiap 5 detik
  }
});
