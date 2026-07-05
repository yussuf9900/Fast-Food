# Système de Gestion de Commande Fast-Food - Architecture MVC Procédurale

Ce projet est un système de commande de fast-food développé selon les principes de la programmation procédurale en PHP. Il respecte une architecture MVC Procédurale stricte répartie en couches distinctes.

## Architecture du Projet

L'application est découpée de la manière suivante :
- `model/` : Fichiers définissant la structure de stockage des entités et les fonctions d'accès direct aux données (CRUD basique).
- `view/` : Fonctions d'affichage HTML (web) et de menus (console).
- `controller/` : Orchestrateur recevant les requêtes ou choix utilisateur, appelant les services et chargeant les vues adaptées.
- `service/` : Couche contenant la logique métier pure, les calculs et les validations.
- `utils/` : Utilitaires système (générateur de référence de commande, outils de saisie en console).

## Structure des Données (Simulée)

Toutes les données sont persistées en session PHP (`$_SESSION`) sous forme de tableaux associatifs.

### Plats
```php
$_SESSION['plats'] = [
    [
        'id' => 1,
        'nom' => 'Burger',
        'prix' => 3500,
        'description' => 'Un délicieux burger'
    ]
]
```

### Commandes
```php
$_SESSION['commandes'] = [
    [
        'id_commande' => 'CMD-1042',
        'client_id' => 1,
        'statut' => 'En attente',
        'lignes' => [
            [
                'id_plat' => 1,
                'quantite' => 2
            ]
        ],
        'id_livreur' => null,
        'paiement' => 'Accepte'
    ]
]
```

### Livreurs
```php
$_SESSION['livreurs'] = [
    [
        'id' => 1,
        'nom' => 'Lamine',
        'disponible' => true
    ]
]
```

## Identifiants de Connexion

- **Client** : Accès direct sans mot de passe.
- **Gérant** : Mot de passe de sécurité requis : **yusuf**

## Installation et Lancement

### 1. Version Console (CLI)
Pour utiliser l'application directement dans votre terminal :
1. Assurez-vous d'être sur la branche `version-console` ou d'exécuter la version globale.
2. Lancez la commande suivante :
   ```bash
   php index.php
   ```

### 2. Version Web (Tailwind UI)
Pour lancer l'interface Web premium :
1. Démarrez le serveur de développement PHP intégré à la racine du projet :
   ```bash
   php -S localhost:8000
   ```
2. Ouvrez votre navigateur et accédez à l'adresse [http://localhost:8000](http://localhost:8000).
