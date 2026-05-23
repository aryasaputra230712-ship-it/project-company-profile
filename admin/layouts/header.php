<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Aurelis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#050816] text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-white/10">
                <h1 class="text-xl font-bold tracking-wider text-aurelis-gold">AURELIS <span class="text-xs font-light block">ADMIN CMS</span></h1>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php" class="flex items-center gap-3 p-3 rounded-lg bg-white/10 text-white transition">
                    <i class="fa-solid fa-gauge w-5"></i> Dashboard
                </a>
                <p class="text-[10px] uppercase tracking-widest text-gray-500 mt-6 mb-2 px-3">Content Management</p>
                <a href="hero_manage.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-images w-5"></i> Hero Slider
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-user-tie w-5"></i> Founder Section
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-gem w-5"></i> Products/Gallery
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <a href="../index.php" class="flex items-center gap-3 p-3 text-red-400 hover:bg-red-500/10 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span>Pages</span> / <span class="text-gray-900 font-medium">Hero Slider</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium">Arya Saputra</span>
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">AS</div>
                </div>
            </header>

            <div class="p-8"></div>