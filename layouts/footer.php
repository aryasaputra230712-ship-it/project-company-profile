<?php
// 2. Deteksi Protokol(Jangan di otak atik)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https" : "http";

// 3. Base URL Pintar
// Ini akan menghasilkan "vibewebs.web.id" di hosting
// Dan "localhost/company_profile" di laptop kamu
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);
?>

    <footer style="background: rgb(17 43 59); color: white; padding: 50px 0;">
        <div style="display: flex; justify-content: space-around;">
            <div>
                <img src="<?= $base_url ?> /assets/imgs/logo_gold.png" alt="Logo" width="100">
                <p>blalbablablablaballbalbal</p>
            </div>

            <div>
                <h3>CONTACT</h3>
                <p>PHONE: </p>
                <p>EMAIL: </p>
                <p></p>
            </div>

            <div>
                <a style="color: white; text-decoration: none;" href="#">Instagram</a>
                <a style="color: white; text-decoration: none;" href="#">Facebook</a>
                <a style="color: white; text-decoration: none;" href="#">Tiktok</a>
            </div>
        </div>
        <br><br>
        
        <hr style="width: 80%; margin-left: 11%;">
        <p align="center">&copy; 2026 Aurelis Jewelry. All rights reserved.</p>
        
    </footer>
    
</body>
</html>