<?php

require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../model/livreur.php';
require_once __DIR__ . '/../service/commande.php';

function afficherEspaceGerantWeb($plats, $commandes, $livreurs, $messageSucces = null, $messageErreur = null, $erreursFormPlat = []) {
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-slate-950 text-slate-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fast-Food Express - Administration</title>
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
                    <span class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs px-2.5 py-1 rounded-full font-semibold">Gérant</span>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="px-4 py-2 bg-slate-850 hover:bg-red-500/10 hover:text-red-400 border border-slate-800 hover:border-red-500/20 rounded-xl text-sm font-semibold transition-all duration-300">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="space-y-6">
                    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6">Ajouter un Plat au Menu</h2>
                        
                        <?php if (!empty($erreursFormPlat)): ?>
                            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-xs space-y-1">
                                <?php foreach ($erreursFormPlat as $err): ?>
                                    <div>• <?php echo htmlspecialchars($err); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="ajouter_plat">
                            
                            <div class="space-y-1">
                                <label for="nom" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nom du plat</label>
                                <input type="text" id="nom" name="nom" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-650 focus:outline-none focus:border-amber-500">
                            </div>

                            <div class="space-y-1">
                                <label for="prix" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Prix (FCFA)</label>
                                <input type="number" id="prix" name="prix" required min="1" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-650 focus:outline-none focus:border-amber-500">
                            </div>

                            <div class="space-y-1">
                                <label for="description" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Description</label>
                                <textarea id="description" name="description" rows="3" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-650 focus:outline-none focus:border-amber-500"></textarea>
                            </div>

                            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-slate-950 font-bold rounded-xl transition-all duration-300 shadow-md">
                                Enregistrer le plat
                            </button>
                        </form>
                    </div>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6">Disponibilité des Livreurs</h2>
                        <div class="space-y-4">
                            <?php foreach ($livreurs as $livreur): ?>
                                <div class="flex items-center justify-between border-b border-slate-800/60 pb-3 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-sm font-semibold text-slate-300">
                                            <?php echo substr($livreur['nom'], 0, 1); ?>
                                        </div>
                                        <span class="text-sm font-semibold text-white"><?php echo htmlspecialchars($livreur['nom']); ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full <?php echo $livreur['disponible'] ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                        <span class="text-xs text-slate-400"><?php echo $livreur['disponible'] ? 'Disponible' : 'En livraison'; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6">Suivi et Traitement des Commandes</h2>
                        
                        <?php if (empty($commandes)): ?>
                            <div class="py-12 text-center text-slate-500 text-sm">
                                Aucune commande n'a été passée pour le moment.
                            </div>
                        <?php else: ?>
                            <div class="space-y-6">
                                <?php foreach (array_reverse($commandes) as $cmd): ?>
                                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-slate-700/80 transition-all duration-300">
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 border-b border-slate-800/60 pb-3">
                                            <div class="flex items-center gap-3">
                                                <span class="font-black text-white text-lg"><?php echo $cmd['id_commande']; ?></span>
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
                                            <span class="text-sm font-extrabold text-amber-400"><?php echo number_format(calculerTotalCommande($cmd['lignes']), 0, ',', ' '); ?> FCFA</span>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="text-sm text-slate-400 space-y-1">
                                                <div class="font-semibold text-slate-200">Articles :</div>
                                                <?php foreach ($cmd['lignes'] as $ligne): ?>
                                                    <?php $plat = getPlatById($ligne['id_plat']); ?>
                                                    <?php if ($plat): ?>
                                                        <div>• <?php echo htmlspecialchars($plat['nom']); ?> <span class="text-slate-500">x<?php echo $ligne['quantite']; ?></span></div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="flex flex-col justify-end gap-3">
                                                <?php if ($cmd['statut'] === 'En attente'): ?>
                                                    <form action="index.php" method="POST">
                                                        <input type="hidden" name="action" value="valider_commande_gerant">
                                                        <input type="hidden" name="id_commande" value="<?php echo $cmd['id_commande']; ?>">
                                                        <button type="submit" class="w-full py-2.5 bg-orange-500 hover:bg-orange-600 active:scale-[0.98] text-slate-950 font-bold rounded-xl transition-all duration-300 text-sm">
                                                            Lancer la Préparation
                                                        </button>
                                                    </form>
                                                <?php elseif ($cmd['statut'] === 'En préparation'): ?>
                                                    <form action="index.php" method="POST" class="flex flex-col sm:flex-row gap-2">
                                                        <input type="hidden" name="action" value="assigner_livreur_gerant">
                                                        <input type="hidden" name="id_commande" value="<?php echo $cmd['id_commande']; ?>">
                                                        
                                                        <select name="id_livreur" required class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm focus:outline-none focus:border-amber-500 text-white">
                                                            <option value="">-- Choisir un livreur --</option>
                                                            <?php foreach ($livreurs as $livreur): ?>
                                                                <?php if ($livreur['disponible']): ?>
                                                                    <option value="<?php echo $livreur['id']; ?>"><?php echo htmlspecialchars($livreur['nom']); ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>

                                                        <button type="submit" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 active:scale-[0.98] text-slate-950 font-bold rounded-xl transition-all duration-300 text-sm whitespace-nowrap">
                                                            Confier la Livraison
                                                        </button>
                                                    </form>
                                                <?php elseif ($cmd['statut'] === 'En livraison'): ?>
                                                    <?php $livreur = getLivreurById($cmd['id_livreur']); ?>
                                                    <div class="text-sm bg-blue-500/5 border border-blue-500/10 text-blue-400 p-3 rounded-xl flex items-center justify-between">
                                                        <span>En cours de livraison par : <strong><?php echo $livreur ? htmlspecialchars($livreur['nom']) : 'Inconnu'; ?></strong></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6">Menu de Plats Actuel</h2>
                        <?php if (empty($plats)): ?>
                            <div class="py-6 text-center text-slate-500 text-sm">
                                Aucun plat enregistré dans le catalogue.
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <?php foreach ($plats as $plat): ?>
                                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-semibold text-white"><?php echo htmlspecialchars($plat['nom']); ?></h4>
                                            <p class="text-xs text-slate-400 line-clamp-2 mt-1"><?php echo htmlspecialchars($plat['description']); ?></p>
                                        </div>
                                        <span class="text-xs font-bold text-amber-400 whitespace-nowrap bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded-full ml-3"><?php echo number_format($plat['prix'], 0, ',', ' '); ?> FCFA</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </body>
    </html>
    <?php
}
