<main>
    <div class="container-main">
        <div class="main-intro">
            <div class="video-bg-container">
                <video class="video-bg" muted autoplay loop playsinline>
                    <source src="<?= BASE_URL ?>/assets/videos/<?= $slides[0]['video_file']; ?>" type="video/mp4">
                </video>
            </div>

            <div class="intro-content">
                <img src="<?= BASE_URL ?>/assets/imgs/logo.png" alt="Logo">
                <h1 id="dynamic-title"><?= $slides[0]['judul']; ?></h1>
                <p id="dynamic-subtitle"><?= $slides[0]['subjudul']; ?></p>
            </div>
        </div>
    </div>
</main>