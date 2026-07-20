# Démarche de réalisation du projet

Objectif : créer un système qui simule un opérateur de Mobile Money (côté opérateur).

Le travail est réparti en deux parties :
- **4208** : mise en place du système (faite).
- **4209** : améliorations et finitions (à venir).

---

# Partie 4208 (moi) — Mise en place du système

## 1. Préparation de l'environnement
- J'ai récupéré le projet CodeIgniter 4 déjà en place (dossier `app`, `public`, `system`, etc.).
- J'ai configuré la base de données pour utiliser **SQLite** dans `app/Config/Database.php` (fichier `writable/mobilemoney.db`).
- J'ai ajouté `writable/*.db` dans `.gitignore` pour ne pas versionner la base.

## 2. Création de la base de données (structure)
J'ai créé 5 tables via des migrations (`app/Database/Migrations/`) :
- `prefixes` : préfixes valides de l'opérateur (033, 037).
- `types_operation` : dépôt, retrait, transfert (+ option frais actifs ou non).
- `baremes` : frais par tranche de montant, liés à un type d'opération.
- `clients` : comptes clients (nom, téléphone, solde).
- `transactions` : historique des opérations (montant, frais, gain opérateur).

## 3. Données de départ (seed)
J'ai créé `app/Database/Seeds/InitialDataSeeder.php` pour insérer automatiquement :
- les préfixes 033 et 037,
- les 3 types d'opération,
- les barèmes de frais donnés en exemple (de 100 à 2 000 000 Ar) pour retrait et transfert.

J'ai aussi écrit le fichier `base.sql` (schéma + données) pour documenter la base.

## 4. Les modèles (logique métier)
Dans `app/Models/` :
- `PrefixeModel` : vérifie si un numéro commence par un préfixe valide.
- `TypeOperationModel` : gère les types d'opération.
- `BaremeModel` : calcule le frais en fonction du montant et de la tranche.
- `ClientModel` : gère les comptes clients et leurs soldes.
- `TransactionModel` : enregistre les opérations et calcule le gain total de l'opérateur.

## 5. Les contrôleurs (traitements)
Dans `app/Controllers/` :
- `Dashboard` : tableau de bord (gain total, nombre de clients, solde global, dernières transactions).
- `Prefixes`, `Types`, `Baremes`, `Clients` : ajout / modification / suppression de chaque élément.
- `Operations` : effectuer un dépôt, retrait ou transfert. Le solde du client est mis à jour et le gain de l'opérateur est enregistré (frais du retrait et du transfert).

## 6. Les vues (interface)
Dans `app/Views/` :
- un `layout.php` commun (menu de navigation),
- une page par fonction : tableau de bord, liste et formulaire des préfixes, types, barèmes, clients,
- la page « Effectuer une opération » et « Historique des opérations ».

## 7. Les routes
J'ai déclaré toutes les routes dans `app/Config/Routes.php` (ex : `prefixes`, `types`, `baremes`, `clients`, `operations`).

## 8. Tests effectués
- `php spark migrate` puis `php spark db:seed InitialDataSeeder` : la base se crée et se remplit.
- `php spark serve` puis test des pages : tout répond correctement.
- Test d'un flux complet (dépôt → transfert → retrait) : les soldes et les gains sont corrects, et un solde insuffisant est bien refusé.

## 9. Résultat (4208)
Le système permet à l'opérateur de :
- configurer ses préfixes valides,
- créer des types d'opération avec des barèmes de frais modifiables,
- suivre la situation des gains (retrait et transfert),
- suivre la situation des comptes clients.

---

# Partie 4209 (à venir) — Améliorations et finitions

Travail prévu pour la suite :
- Ajouter une authentification / espace sécurisé pour l'opérateur.
- Ajouter des filtres et de la recherche dans l'historique des transactions.
- Exporter les rapports de gain (CSV / PDF).
- Ajouter des graphiques d'évolution des gains.
- Gérer les annulations et remboursements de transactions.
- Tests automatisés (PHPUnit).
