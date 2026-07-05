<?php

require_once __DIR__ . '/../model/plat.php';
require_once __DIR__ . '/../model/commande.php';
require_once __DIR__ . '/../model/livreur.php';
require_once __DIR__ . '/../model/client.php';
require_once __DIR__ . '/../service/commande.php';

function afficherEspaceGerantWeb($plats, $commandes, $livreurs, $messageSucces = null, $messageErreur = null, $erreursFormPlat = []) {
    // Calculate stats
    $totalVentes = 0;
    foreach ($commandes as $cmd) {
        $totalVentes += calculerTotalCommande($cmd['lignes']);
    }

    $activeCount = 0;
    foreach ($commandes as $cmd) {
        if ($cmd['statut'] !== 'Livrée') {
            $activeCount++;
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr" class="h-full bg-[#f4f6fa] text-slate-800">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fast-Food Express - Administration</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="min-h-full flex flex-col bg-[#f4f6fa]">
        <!-- Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm border-b-4 border-[#c2272d]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-800">
                        🍔 <span class="text-[#c2272d]">FAST-FOOD</span> ADMIN PANEL
                    </span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#commandes" class="text-sm font-bold text-[#c2272d] border-b-2 border-[#c2272d] pb-1">Commandes</a>
                    <a href="#menu-plats" class="text-sm font-bold text-slate-500 hover:text-slate-800">Menu</a>
                    
                    <form action="index.php" method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="text-sm font-bold text-[#c2272d] hover:text-red-750 transition-all flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            <?php if ($messageSucces): ?>
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold shadow-sm animate-fade-in">
                    <?php echo htmlspecialchars($messageSucces); ?>
                </div>
            <?php endif; ?>
            <?php if ($messageErreur): ?>
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold shadow-sm animate-fade-in">
                    <?php echo htmlspecialchars($messageErreur); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left Column: Commandes en attente -->
                <div class="lg:col-span-2 space-y-6" id="commandes">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                            📦 COMMANDES EN ATTENTE (<?php echo $activeCount; ?>)
                        </h2>
                    </div>

                    <?php if ($activeCount === 0): ?>
                        <div class="py-12 bg-white rounded-3xl border border-slate-100 text-center text-slate-400 text-sm shadow-sm">
                            Aucune commande active pour le moment.
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach (array_reverse($commandes) as $cmd): ?>
                                <?php if ($cmd['statut'] === 'Livrée') continue; ?>
                                
                                <?php 
                                // Left border status style
                                $borderClass = 'border-l-8 border-slate-300';
                                if ($cmd['statut'] === 'En attente') {
                                    $borderClass = 'border-l-8 border-[#a16207]';
                                } elseif ($cmd['statut'] === 'En préparation') {
                                    $borderClass = 'border-l-8 border-[#0f766e]';
                                } elseif ($cmd['statut'] === 'En livraison') {
                                    $borderClass = 'border-l-8 border-blue-500';
                                }

                                // Get Client Name
                                $client = getClientById($cmd['client_id']);
                                $clientName = $client ? $client['nom'] : 'Client Anonyme';

                                // Get plat names list
                                $platNoms = [];
                                foreach ($cmd['lignes'] as $ligne) {
                                    $plat = getPlatById($ligne['id_plat']);
                                    if ($plat) {
                                        $platNoms[] = htmlspecialchars($plat['nom']) . ($ligne['quantite'] > 1 ? ' x' . $ligne['quantite'] : '');
                                    }
                                }
                                $platNomsStr = implode(', ', $platNoms);
                                ?>

                                <div class="bg-white <?php echo $borderClass; ?> rounded-2xl shadow-sm p-6 flex flex-col md:flex-row justify-between md:items-center gap-4 hover:shadow-md transition-all duration-300">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-800 text-sm">#<?php echo $cmd['id_commande']; ?></span>
                                            <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-semibold">
                                                <?php echo htmlspecialchars($cmd['heure'] ?? date('H:i')); ?>
                                            </span>
                                            <?php if ($cmd['statut'] === 'En préparation'): ?>
                                                <span class="text-[10px] bg-[#0f766e] text-white px-2 py-0.5 rounded font-bold uppercase tracking-wider">PRÊTE</span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-xl font-extrabold text-[#991b1b]"><?php echo $platNomsStr; ?></h3>
                                        <p class="text-sm text-slate-400 font-semibold">Client : <?php echo htmlspecialchars($clientName); ?></p>
                                    </div>

                                    <div class="flex flex-col md:items-end gap-3 min-w-[200px]">
                                        <?php if ($cmd['statut'] === 'En attente'): ?>
                                            <form action="index.php" method="POST" class="w-full">
                                                <input type="hidden" name="action" value="valider_commande_gerant">
                                                <input type="hidden" name="id_commande" value="<?php echo $cmd['id_commande']; ?>">
                                                <button type="submit" class="w-full py-3 bg-[#c2272d] hover:bg-[#a61f24] active:scale-[0.98] text-white font-extrabold rounded-xl transition-all duration-300 text-xs tracking-wider uppercase shadow-sm">
                                                    Valider la commande
                                                </button>
                                            </form>
                                        <?php elseif ($cmd['statut'] === 'En préparation'): ?>
                                            <form action="index.php" method="POST" class="w-full space-y-2">
                                                <input type="hidden" name="action" value="assigner_livreur_gerant">
                                                <input type="hidden" name="id_commande" value="<?php echo $cmd['id_commande']; ?>">
                                                
                                                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-2">
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide">Assigner un livreur</label>
                                                    <div class="flex gap-2">
                                                        <select name="id_livreur" required class="flex-1 bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-[#a16207]">
                                                            <option value="">-- Choisir --</option>
                                                            <?php foreach ($livreurs as $livreur): ?>
                                                                <?php if ($livreur['disponible']): ?>
                                                                    <option value="<?php echo $livreur['id']; ?>"><?php echo htmlspecialchars($livreur['nom']); ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="submit" class="bg-[#854d0e] hover:bg-[#713f12] text-white font-bold px-3 py-1.5 rounded-lg text-xs uppercase tracking-wide transition-colors">
                                                            Assigner
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php elseif ($cmd['statut'] === 'En livraison'): ?>
                                            <?php $livreur = getLivreurById($cmd['id_livreur']); ?>
                                            <div class="text-xs bg-blue-50 border border-blue-100 text-blue-600 p-3 rounded-xl flex items-center justify-between gap-3 w-full font-semibold">
                                                <span>En livraison par : <strong><?php echo $livreur ? htmlspecialchars($livreur['nom']) : 'Livreur'; ?></strong></span>
                                                <span class="flex h-2.5 w-2.5 relative flex-shrink-0">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-450 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-550"></span>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Form adding plat & stats -->
                <div class="space-y-6">
                    
                    <!-- Form ajouter plat -->
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm space-y-6">
                        <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            📝 AJOUTER UN NOUVEAU PLAT
                        </h2>
                        
                        <?php if (!empty($erreursFormPlat)): ?>
                            <div class="p-3 bg-red-50 border border-red-200 text-red-650 rounded-xl text-xs space-y-1 font-semibold">
                                <?php foreach ($erreursFormPlat as $err): ?>
                                    <div>• <?php echo htmlspecialchars($err); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="ajouter_plat">
                            
                            <div class="space-y-1">
                                <label for="nom" class="text-xs text-slate-500 font-bold uppercase tracking-wider">Nom du plat</label>
                                <input type="text" id="nom" name="nom" placeholder="ex: Double Cheese Burger" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0f766e]">
                            </div>

                            <div class="space-y-1">
                                <label for="prix" class="text-xs text-slate-500 font-bold uppercase tracking-wider">Prix (CFA)</label>
                                <div class="relative">
                                    <input type="number" id="prix" name="prix" placeholder="3500" required min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0f766e] pr-12">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">CFA</span>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label for="description" class="text-xs text-slate-500 font-bold uppercase tracking-wider">Description</label>
                                <textarea id="description" name="description" rows="3" placeholder="Pain brioché, double steak..." required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0f766e]"></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-[#005f5f] hover:bg-[#004d4d] active:scale-[0.98] text-white font-extrabold rounded-xl transition-all duration-300 shadow-sm text-xs tracking-wider uppercase flex items-center justify-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter au catalogue
                            </button>
                        </form>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#fce7f3] border border-[#fbcfe8] rounded-2xl p-5 shadow-sm">
                            <div class="text-[10px] font-bold text-red-800 uppercase tracking-widest">Ventes du jour</div>
                            <div class="text-lg font-black text-red-800 mt-1.5 whitespace-nowrap">
                                <?php echo number_format($totalVentes, 0, ',', '.'); ?> CFA
                            </div>
                        </div>
                        <div class="bg-[#e0f2fe] border border-[#bae6fd] rounded-2xl p-5 shadow-sm">
                            <div class="text-[10px] font-bold text-sky-800 uppercase tracking-widest">Livreurs actifs</div>
                            <div class="text-2xl font-black text-sky-850 mt-1">
                                <?php echo count($livreurs); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Livreurs panel -->
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm space-y-4">
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                            Livreurs & Disponibilité
                        </h2>
                        <div class="space-y-3">
                            <?php foreach ($livreurs as $livreur): ?>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-650">
                                            <?php echo substr($livreur['nom'], 0, 1); ?>
                                        </div>
                                        <span class="text-xs font-bold text-slate-850"><?php echo htmlspecialchars($livreur['nom']); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full <?php echo $livreur['disponible'] ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                        <span class="text-[10px] font-semibold text-slate-400"><?php echo $livreur['disponible'] ? 'Dispo' : 'En course'; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

            </div>

            <!-- List Menu Plats (bottom for admin view) -->
            <div id="menu-plats" class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm space-y-6">
                <h2 class="text-xl font-black text-slate-800 uppercase">Menu Actuel</h2>
                <?php if (empty($plats)): ?>
                    <div class="py-6 text-center text-slate-450 text-sm">
                        Aucun plat dans le catalogue.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($plats as $plat): ?>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-150 border border-slate-200 flex-shrink-0">
                                        <img src="<?php echo htmlspecialchars($plat['image'] ?? 'images/burger_xl.jpg'); ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 leading-tight"><?php echo htmlspecialchars($plat['nom']); ?></h4>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5"><?php echo htmlspecialchars($plat['description']); ?></p>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-[#c2272d] whitespace-nowrap bg-red-50 border border-red-100 px-2.5 py-1 rounded-full">
                                    <?php echo number_format($plat['prix'], 0, ',', ' '); ?> CFA
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- List of Livrées orders (archive) -->
            <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm space-y-6">
                <h2 class="text-sm font-black text-slate-500 uppercase tracking-widest">Historique des Commandes Livrées</h2>
                <?php 
                $archiveCount = 0;
                foreach ($commandes as $cmd) {
                    if ($cmd['statut'] === 'Livrée') $archiveCount++;
                }
                if ($archiveCount === 0): ?>
                    <div class="py-4 text-center text-slate-400 text-xs">
                        Aucune commande livrée pour le moment.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach (array_reverse($commandes) as $cmd): ?>
                            <?php if ($cmd['statut'] !== 'Livrée') continue; ?>
                            <?php 
                            $client = getClientById($cmd['client_id']);
                            $clientName = $client ? $client['nom'] : 'Client Anonyme';
                            $platNoms = [];
                            foreach ($cmd['lignes'] as $ligne) {
                                $plat = getPlatById($ligne['id_plat']);
                                if ($plat) {
                                    $platNoms[] = htmlspecialchars($plat['nom']) . ' x' . $ligne['quantite'];
                                }
                            }
                            $platNomsStr = implode(', ', $platNoms);
                            ?>
                            <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between border border-slate-100 text-xs">
                                <div>
                                    <div class="font-bold text-slate-900">#<?php echo $cmd['id_commande']; ?> - <?php echo $platNomsStr; ?></div>
                                    <div class="text-slate-400 mt-1">Client : <?php echo htmlspecialchars($clientName); ?></div>
                                </div>
                                <span class="text-green-600 font-bold bg-green-50 border border-green-150 px-2 py-0.5 rounded-full">Livrée</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <?php
        $aDesLivraisonsEnCours = false;
        foreach ($commandes as $cmd) {
            if ($cmd['statut'] === 'En livraison' || $cmd['statut'] === 'En préparation' || $cmd['statut'] === 'En attente') {
                $aDesLivraisonsEnCours = true;
                break;
            }
        }
        if ($aDesLivraisonsEnCours): ?>
            <!-- Auto-refresh every 5 seconds to get updates on states -->
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
