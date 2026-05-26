<?php
session_start();
// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurelis Admin | Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #030408;
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.01) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen p-4 select-none">

    <div class="w-full max-w-[440px]">
        <div class="bg-[#0b0c10] border border-amber-950/20 p-8 md:p-10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] text-center relative backdrop-blur-md">

            <div class="w-16 h-16 bg-gradient-to-br from-[#f7c66b] to-[#bfa37e] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-[0_0_30px_rgba(247,198,107,0.25)]">
                <i class="fa-solid fa-gem text-2xl text-[#050816]"></i>
            </div>

            <h1 class="text-white text-2xl font-serif tracking-widest mb-1">Aurelis Admin</h1>
            <p class="text-gray-500 text-xs font-light tracking-wide mb-8">Dashboard Management Panel</p>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                <div class="mb-6 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> Login Gagal! Periksa kembali akun Anda.
                </div>
            <?php endif; ?>

            <div class="flex items-center mb-6">
                <div class="flex-1 border-t border-white/5"></div>
                <span class="px-3 text-[10px] tracking-[2px] text-gray-500 font-bold uppercase">Masuk Ke Akun</span>
                <div class="flex-1 border-t border-white/5"></div>
            </div>

            <form action="process/process_login.php" method="POST" class="space-y-5 text-left">
                <div>
                    <label class="text-[11px] font-medium text-gray-400 tracking-wider block mb-2">Username</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-600 text-sm">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="username" placeholder="Masukkan username" required
                            class="w-full bg-[#14161f] border border-white/5 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-gray-700 outline-none focus:border-[#f7c66b]/30 focus:bg-[#181b26] transition duration-300">
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-medium text-gray-400 tracking-wider block mb-2">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-600 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="passwordField" name="password" placeholder="••••••••" required
                            class="w-full bg-[#14161f] border border-white/5 rounded-xl pl-12 pr-12 py-3.5 text-sm text-white placeholder-gray-700 outline-none focus:border-[#f7c66b]/30 focus:bg-[#181b26] transition duration-300">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 text-gray-600 hover:text-gray-400 transition text-sm">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#f7c66b] to-[#bfa37e] text-[#050816] font-bold py-4 rounded-xl uppercase text-xs tracking-[2px] hover:brightness-110 active:scale-[0.99] transition duration-300 shadow-xl mt-2 block text-center">
                    Masuk Dashboard
                </button>
            </form>
        </div>

        <p class="text-center text-[10px] text-gray-600 tracking-widest mt-8 uppercase">
            © 2026 Aurelis Jewelry · Admin Panel v1.0
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>