<?php

require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../service/commande.php';

function afficherEspaceClientWeb($plats, $panier, $commandesClient, $messageSucces = null, $messageErreur = null) {
    $totalPanier = calculerTotalCommande($panier);
    $nombreArticles = 0;
    foreach ($panier as $ligne) {
        $nombreArticles += $ligne['quantite'];
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-[#f4f6fa] text-slate-800">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fast-Food App</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="min-h-full flex flex-col bg-[#f4f6fa]">
        <!-- Header -->
        <header class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-800">
                        🍔 <span class="text-[#c2272d]">Fast-Food</span> App
                    </span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#menu" class="text-sm font-bold text-[#c2272d] border-b-2 border-[#c2272d] pb-1">Accueil</a>
                    <a href="#commandes" class="text-sm font-bold text-slate-500 hover:text-slate-800">Historique</a>
                    
                    <a href="#panier" class="px-5 py-2.5 bg-[#c2272d] hover:bg-[#a61f24] text-white font-bold rounded-full text-sm transition-all duration-300 shadow-md flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Panier (<?php echo $nombreArticles; ?>)
                    </a>

                    <form action="index.php" method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 rounded-xl transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">
            <?php if ($messageSucces): ?>
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold shadow-sm">
                    <?php echo htmlspecialchars($messageSucces); ?>
                </div>
            <?php endif; ?>
            <?php if ($messageErreur): ?>
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold shadow-sm">
                    <?php echo htmlspecialchars($messageErreur); ?>
                </div>
            <?php endif; ?>

            <!-- Menu Section -->
            <div id="menu" class="space-y-6">
                <div>
                    <h2 class="text-xs font-black tracking-widest text-slate-400 uppercase">Notre menu complet</h2>
                    <p class="text-sm text-slate-500 mt-1 font-semibold">Fait maison, avec amour et rapidité.</p>
                </div>

                <?php if (empty($plats)): ?>
                    <div class="p-8 bg-white border border-slate-100 rounded-3xl text-center text-slate-400">
                        Aucun plat n'est disponible actuellement.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($plats as $plat): ?>
                            <div class="bg-white border border-slate-100 rounded-3xl p-6 flex flex-col justify-between hover:shadow-md transition-all duration-300">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                                    <p class="text-sm text-slate-400 leading-relaxed mb-4"><?php echo htmlspecialchars($plat['description']); ?></p>
                                    <div class="text-[#b45309] font-extrabold text-sm mb-4">
                                        💰 <?php echo number_format($plat['prix'], 0, ',', ' '); ?> CFA
                                    </div>
                                </div>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="action" value="ajouter_panier">
                                    <input type="hidden" name="id_plat" value="<?php echo $plat['id']; ?>">
                                    <input type="hidden" name="quantite" value="1">
                                    <button type="submit" class="w-full py-2.5 bg-[#c2272d] hover:bg-[#a61f24] active:scale-[0.98] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-sm flex items-center justify-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Ajouter au panier
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Panier Section -->
            <div id="panier" class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="bg-[#eaf0f9] px-6 py-4 border-b border-slate-100 flex items-center gap-2 text-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="font-extrabold uppercase text-sm tracking-wider">Votre Panier</h3>
                </div>

                <div class="p-6">
                    <?php if (empty($panier)): ?>
                        <div class="py-12 text-center text-slate-400 text-sm">
                            Votre panier est vide. Sélectionnez des articles dans notre menu.
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($panier as $ligne): ?>
                                <?php $plat = getPlatById($ligne['id_plat']); ?>
                                <?php if ($plat): ?>
                                    <div class="flex items-center justify-between border-b border-slate-100/60 pb-5 last:border-0 last:pb-0">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 border border-slate-150 flex-shrink-0">
                                                <img src="<?php echo htmlspecialchars($plat['image'] ?? 'images/burger_xl.jpg'); ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h4 class="text-base font-bold text-slate-900"><?php echo $ligne['quantite']; ?>x <?php echo htmlspecialchars($plat['nom']); ?></h4>
                                                <p class="text-xs text-slate-400 mt-0.5">Avec amour</p>
                                                <form action="index.php" method="POST" class="inline mt-1 block">
                                                    <input type="hidden" name="action" value="supprimer_panier">
                                                    <input type="hidden" name="id_plat" value="<?php echo $plat['id']; ?>">
                                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline transition-all">
                                                        Retirer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <span class="text-base font-extrabold text-slate-900">
                                            <?php echo number_format($plat['prix'] * $ligne['quantite'], 0, ',', ' '); ?> CFA
                                        </span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <div class="border-t border-dashed border-slate-200 pt-6 flex justify-between items-center text-sm font-bold text-slate-700">
                                <span class="uppercase tracking-wider">Total à payer</span>
                                <span class="text-xl font-black text-[#c2272d]">
                                    <?php echo number_format($totalPanier, 0, ',', ' '); ?> CFA
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paiement Section -->
            <?php if (!empty($panier)): ?>
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="font-extrabold text-slate-800 uppercase text-sm tracking-wider">Paiement en ligne</h3>
                    </div>

                    <form action="index.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="valider_commande">
                        
                        <div class="space-y-1">
                            <label for="card_number" class="text-xs font-bold uppercase tracking-wider text-slate-400">Numéro de carte</label>
                            <div class="relative">
                                <input type="text" id="card_number" required placeholder="0000 0000 0000 0000" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-450 focus:outline-none focus:border-[#c2272d] focus:ring-1 focus:ring-[#c2272d]">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="card_exp" class="text-xs font-bold uppercase tracking-wider text-slate-400">Expiration</label>
                                <input type="text" id="card_exp" required placeholder="MM/YY" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-450 focus:outline-none focus:border-[#c2272d] focus:ring-1 focus:ring-[#c2272d] text-center">
                            </div>
                            <div class="space-y-1">
                                <label for="card_cvv" class="text-xs font-bold uppercase tracking-wider text-slate-400">CVV</label>
                                <input type="password" id="card_cvv" required placeholder="***" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-450 focus:outline-none focus:border-[#c2272d] focus:ring-1 focus:ring-[#c2272d] text-center">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 py-4 bg-[#c2272d] hover:bg-[#a61f24] active:scale-[0.98] text-white font-extrabold rounded-2xl shadow-lg shadow-red-700/10 hover:shadow-red-700/25 transition-all duration-300 uppercase tracking-wide text-sm">
                            Valider et Payer <?php echo number_format($totalPanier, 0, ',', ' '); ?> CFA
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Suivi des commandes -->
            <div id="commandes" class="space-y-6">
                <div>
                    <h2 class="text-xs font-black tracking-widest text-slate-400 uppercase">Suivi de vos commandes</h2>
                    <p class="text-sm text-slate-500 mt-1 font-semibold">Consultez le statut de vos commandes en temps réel.</p>
                </div>

                <?php if (empty($commandesClient)): ?>
                    <div class="p-8 bg-white border border-slate-100 rounded-3xl text-center text-slate-400 text-sm">
                        Vous n'avez pas encore passé de commande.
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_reverse($commandesClient) as $cmd): ?>
                            <div class="bg-white border border-slate-100 rounded-2xl p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:shadow-sm transition-all duration-200">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-bold text-slate-900">#<?php echo $cmd['id_commande']; ?></span>
                                        <?php if (isset($cmd['heure'])): ?>
                                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-semibold"><?php echo $cmd['heure']; ?></span>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        $statutClass = 'bg-slate-150 text-slate-600';
                                        if ($cmd['statut'] === 'En attente') $statutClass = 'bg-amber-50 text-amber-600 border border-amber-100';
                                        elseif ($cmd['statut'] === 'En préparation') $statutClass = 'bg-orange-50 text-orange-600 border border-orange-100';
                                        elseif ($cmd['statut'] === 'En livraison') $statutClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                                        elseif ($cmd['statut'] === 'Livrée') $statutClass = 'bg-green-50 text-green-600 border border-green-100';
                                        ?>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold <?php echo $statutClass; ?>">
                                            <?php echo $cmd['statut']; ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500 font-semibold">
                                        Total : <?php echo number_format(calculerTotalCommande($cmd['lignes']), 0, ',', ' '); ?> CFA
                                    </div>
                                </div>
                                <div class="text-xs font-semibold text-slate-500 flex items-center gap-2">
                                    <?php if ($cmd['statut'] === 'En livraison'): ?>
                                        <span class="flex h-2 w-2 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                        </span>
                                        En cours de livraison
                                    <?php elseif ($cmd['statut'] === 'Livrée'): ?>
                                        <span class="text-green-500">✓ Livrée avec succès</span>
                                    <?php elseif ($cmd['statut'] === 'En préparation'): ?>
                                        <span class="text-orange-500">En cours de préparation...</span>
                                    <?php else: ?>
                                        <span>En attente de validation</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <?php
        $aDesLivraisonsEnCours = false;
        foreach ($commandesClient as $cmd) {
            if ($cmd['statut'] === 'En livraison' || $cmd['statut'] === 'En préparation' || $cmd['statut'] === 'En attente') {
                $aDesLivraisonsEnCours = true;
                break;
            }
        }
        if ($aDesLivraisonsEnCours): ?>
            <!-- Auto-refresh every 5 seconds to get updates on the delivery state -->
            <script>
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
            </script>
        <?php endif; ?>
    </body>
    </html>
    <?php
}
