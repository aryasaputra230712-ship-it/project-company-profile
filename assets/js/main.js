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

  // --- LOGIKA MOBILE MENU (Perbaikan di Sini) ---
  const hamburgerBtn = document.getElementById("hamburger-btn");
  const closeBtn = document.getElementById("close-menu");
  const mobileMenu = document.getElementById("mobile-menu");
  const navIcons = document.getElementById("nav-icons"); // Wadah ikon user & cart
  const mobileLinks = document.querySelectorAll(".mobile-link");

  // Fungsi Buka Menu
  if (hamburgerBtn) {
    hamburgerBtn.addEventListener("click", () => {
      // Munculkan Menu
      mobileMenu.classList.remove("-translate-y-full", "opacity-0", "pointer-events-none");
      mobileMenu.classList.add("translate-y-0", "opacity-100", "pointer-events-auto");

      // Sembunyikan ikon nav asli agar tidak tabrakan dengan tombol X
      if (navIcons) navIcons.classList.add("opacity-0");

      document.body.style.overflow = "hidden";
    });
  }

  // Fungsi Tutup Menu
  const closeMenuAction = () => {
    mobileMenu.classList.remove("translate-y-0", "opacity-100", "pointer-events-auto");
    mobileMenu.classList.add("-translate-y-full", "opacity-0", "pointer-events-none");

    // Munculkan kembali ikon nav
    if (navIcons) navIcons.classList.remove("opacity-0");

    document.body.style.overflow = "";
  };

  if (closeBtn) {
    closeBtn.addEventListener("click", closeMenuAction);
  }

  // Tutup menu otomatis jika link diklik
  mobileLinks.forEach((link) => {
    link.addEventListener("click", closeMenuAction);
  });
});
