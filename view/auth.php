<?php

function afficherMenuConnexionWeb($erreur = null) {
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-slate-950 text-slate-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion - Fast-Food System</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="h-full flex items-center justify-center bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-amber-900/40 via-slate-950 to-slate-950">
        <div class="w-full max-w-md p-8 bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 shadow-2xl shadow-amber-950/20">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 bg-amber-500/10 rounded-2xl mb-4 border border-amber-500/20 text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">
                    Fast-Food Express
                </h1>
                <p class="text-slate-400 mt-2 text-sm">Sélectionnez votre espace pour continuer</p>
            </div>

            <?php if ($erreur): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-sm text-center">
                    <?php echo htmlspecialchars($erreur); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-4 mb-8 bg-slate-950 p-1.5 rounded-2xl border border-slate-800">
                <button onclick="switchTab('client')" id="btn-client" class="py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 bg-amber-500 text-slate-950 shadow-md">
                    Client
                </button>
                <button onclick="switchTab('gerant')" id="btn-gerant" class="py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 text-slate-400 hover:text-slate-200">
                    Gérant
                </button>
            </div>

            <form action="index.php" method="POST" id="form-client" class="space-y-6">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="client">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-bold rounded-2xl shadow-lg hover:shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    Accéder à l'Espace Client
                </button>
            </form>

            <form action="index.php" method="POST" id="form-gerant" class="space-y-6 hidden">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="gerant">
                
                <div class="space-y-2">
                    <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mot de passe de sécurité</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-slate-100 placeholder-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300">
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-bold rounded-2xl shadow-lg hover:shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
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
                    btnClient.className = "py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 bg-amber-500 text-slate-950 shadow-md";
                    btnGerant.className = "py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 text-slate-400 hover:text-slate-200";
                    formClient.classList.remove('hidden');
                    formGerant.classList.add('hidden');
                } else {
                    btnGerant.className = "py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 bg-amber-500 text-slate-950 shadow-md";
                    btnClient.className = "py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 text-slate-400 hover:text-slate-200";
                    formGerant.classList.remove('hidden');
                    formClient.classList.add('hidden');
                }
            }
        </script>
    </body>
    </html>
    <?php
}
