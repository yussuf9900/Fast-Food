<?php

function afficherMenuConnexionWeb($erreur = null) {
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-[#f4f6fa] text-slate-800">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion - Fast-Food Express</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;850;900&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="h-full flex items-center justify-center bg-[#f4f6fa]">
        <div class="w-full max-w-md p-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 bg-red-50 rounded-2xl mb-4 border border-red-100 text-[#c2272d]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">
                    🍔 <span class="text-[#c2272d]">Fast-Food</span> App
                </h1>
                <p class="text-slate-500 mt-2 text-sm">Sélectionnez votre espace pour continuer</p>
            </div>

            <?php if ($erreur): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-650 rounded-2xl text-sm text-center font-semibold">
                    <?php echo htmlspecialchars($erreur); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-2 mb-8 bg-slate-100 p-1.5 rounded-2xl border border-slate-200/40">
                <button onclick="switchTab('client')" id="btn-client" class="py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 bg-white text-[#c2272d] shadow-sm">
                    Client
                </button>
                <button onclick="switchTab('gerant')" id="btn-gerant" class="py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 text-slate-500 hover:text-slate-800">
                    Gérant
                </button>
            </div>

            <form action="index.php" method="POST" id="form-client" class="space-y-6">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="client">
                <button type="submit" class="w-full py-4 bg-[#c2272d] hover:bg-[#a61f24] active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-red-700/10 hover:shadow-red-700/25 transition-all duration-300">
                    Accéder à l'Espace Client
                </button>
            </form>

            <form action="index.php" method="POST" id="form-gerant" class="space-y-6 hidden">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="gerant">
                
                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-500">Mot de passe de sécurité</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c2272d] focus:ring-1 focus:ring-[#c2272d] transition-all duration-300">
                </div>

                <button type="submit" class="w-full py-4 bg-[#c2272d] hover:bg-[#a61f24] active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-red-700/10 hover:shadow-red-700/25 transition-all duration-300">
                    S'authentifier
                </button>
            </form>
        </div>

        <script>
            function switchTab(role) {
                const btnClient = document.getElementById('btn-client');
                const btnGerant = document.getElementById('btn-gerant');
                const formClient = document.getElementById('form-client');
                const formGerant = document.getElementById('form-gerant');

                if (role === 'client') {
                    btnClient.className = "py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 bg-white text-[#c2272d] shadow-sm";
                    btnGerant.className = "py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 text-slate-500 hover:text-slate-800";
                    formClient.classList.remove('hidden');
                    formGerant.classList.add('hidden');
                } else {
                    btnGerant.className = "py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 bg-white text-[#c2272d] shadow-sm";
                    btnClient.className = "py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 text-slate-500 hover:text-slate-800";
                    formGerant.classList.remove('hidden');
                    formClient.classList.add('hidden');
                }
            }
        </script>
    </body>
    </html>
    <?php
}
