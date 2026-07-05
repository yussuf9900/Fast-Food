<?php

require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../service/commande.php';

function afficherEspaceClientWeb($plats, $panier, $commandesClient, $messageSucces = null, $messageErreur = null) {
    $totalPanier = calculerTotalCommande($panier);
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-slate-950 text-slate-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fast-Food Express - Espace Client</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="h-full flex flex-col bg-slate-950">
        <header class="bg-slate-900/60 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Fast-Food Express</span>
                    <span class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs px-2.5 py-1 rounded-full font-semibold">Client</span>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="px-4 py-2 bg-slate-850 hover:bg-red-500/10 hover:text-red-400 border border-slate-800 hover:border-red-500/20 rounded-xl text-sm font-semibold transition-all duration-300">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <?php if ($messageSucces): ?>
                    <div class="p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-2xl text-sm">
                        <?php echo htmlspecialchars($messageSucces); ?>
                    </div>
                <?php endif; ?>
                <?php if ($messageErreur): ?>
                    <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-sm">
                        <?php echo htmlspecialchars($messageErreur); ?>
                    </div>
                <?php endif; ?>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-white mb-6">Notre Menu Gourmand</h2>
                    <?php if (empty($plats)): ?>
                        <div class="p-8 bg-slate-900/40 border border-slate-800 rounded-3xl text-center text-slate-400">
                            Aucun plat n'est disponible actuellement. Revenez plus tard !
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <?php foreach ($plats as $plat): ?>
                                <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-amber-500/30 transition-all duration-300 shadow-lg hover:shadow-amber-500/5">
                                    <div>
                                        <div class="flex justify-between items-start gap-4 mb-3">
                                            <h3 class="text-lg font-bold text-white"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                                            <span class="text-amber-400 font-extrabold text-lg whitespace-nowrap"><?php echo number_format($plat['prix'], 0, ',', ' '); ?> FCFA</span>
                                        </div>
                                        <p class="text-sm text-slate-400 leading-relaxed mb-6"><?php echo htmlspecialchars($plat['description']); ?></p>
                                    </div>
                                    <form action="index.php" method="POST" class="flex gap-3">
                                        <input type="hidden" name="action" value="ajouter_panier">
                                        <input type="hidden" name="id_plat" value="<?php echo $plat['id']; ?>">
                                        <input type="number" name="quantite" value="1" min="1" class="w-16 px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-center focus:outline-none focus:border-amber-500 text-white font-bold">
                                        <button type="submit" class="flex-1 py-2 px-4 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-slate-950 font-bold rounded-xl transition-all duration-300 shadow-md">
                                            Ajouter
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-white mb-6">Suivi de vos commandes</h2>
                    <?php if (empty($commandesClient)): ?>
                        <div class="p-8 bg-slate-900/40 border border-slate-800 rounded-3xl text-center text-slate-400 text-sm">
                            Vous n'avez pas encore passé de commande.
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach (array_reverse($commandesClient) as $cmd): ?>
                                <div class="bg-slate-900/40 border border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="font-bold text-white"><?php echo $cmd['id_commande']; ?></span>
                                            <?php 
                                            $statutClass = 'bg-slate-800 text-slate-400';
                                            if ($cmd['statut'] === 'En attente') $statutClass = 'bg-amber-500/10 border border-amber-500/20 text-amber-400';
                                            elseif ($cmd['statut'] === 'En préparation') $statutClass = 'bg-orange-500/10 border border-orange-500/20 text-orange-400';
                                            elseif ($cmd['statut'] === 'En livraison') $statutClass = 'bg-blue-500/10 border border-blue-500/20 text-blue-400';
                                            ?>
                                            <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold <?php echo $statutClass; ?>">
                                                <?php echo $cmd['statut']; ?>
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            Total : <?php echo number_format(calculerTotalCommande($cmd['lignes']), 0, ',', ' '); ?> FCFA
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        <?php if ($cmd['id_livreur']): ?>
                                            Livreur assigné
                                        <?php else: ?>
                                            En cours d'affectation
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 sticky top-24 shadow-2xl">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center justify-between">
                        <span>Mon Panier</span>
                        <span class="bg-slate-800 text-amber-400 text-xs px-2.5 py-1 rounded-full font-bold">
                            <?php echo count($panier); ?> articles
                        </span>
                    </h2>

                    <?php if (empty($panier)): ?>
                        <div class="py-12 text-center text-slate-500 text-sm">
                            Votre panier est vide. Sélectionnez de délicieux plats à gauche pour commencer.
                        </div>
                    <?php else: ?>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 mb-6">
                            <?php foreach ($panier as $ligne): ?>
                                <?php $plat = getPlatById($ligne['id_plat']); ?>
                                <?php if ($plat): ?>
                                    <div class="flex items-center justify-between border-b border-slate-800/60 pb-3 last:border-0 last:pb-0">
                                        <div>
                                            <h4 class="text-sm font-semibold text-white"><?php echo htmlspecialchars($plat['nom']); ?></h4>
                                            <span class="text-xs text-slate-400"><?php echo number_format($plat['prix'], 0, ',', ' '); ?> FCFA x <?php echo $ligne['quantite']; ?></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-bold text-white"><?php echo number_format($plat['prix'] * $ligne['quantite'], 0, ',', ' '); ?> FCFA</span>
                                            <form action="index.php" method="POST">
                                                <input type="hidden" name="action" value="supprimer_panier">
                                                <input type="hidden" name="id_plat" value="<?php echo $plat['id']; ?>">
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="border-t border-slate-800 pt-6 space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Sous-total</span>
                                <span class="font-semibold text-white"><?php echo number_format($totalPanier, 0, ',', ' '); ?> FCFA</span>
                            </div>
                            <div class="flex justify-between items-center text-base border-t border-slate-800/40 pt-4">
                                <span class="text-slate-200 font-bold">Total à payer</span>
                                <span class="text-xl font-black text-amber-400"><?php echo number_format($totalPanier, 0, ',', ' '); ?> FCFA</span>
                            </div>

                            <form action="index.php" method="POST" class="pt-4">
                                <input type="hidden" name="action" value="valider_commande">
                                <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-extrabold rounded-2xl shadow-lg hover:shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                                    Simuler le paiement
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
        $aDesLivraisonsEnCours = false;
        foreach ($commandesClient as $cmd) {
            if ($cmd['statut'] === 'En livraison') {
                $aDesLivraisonsEnCours = true;
                break;
            }
        }
        if ($aDesLivraisonsEnCours): ?>
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
