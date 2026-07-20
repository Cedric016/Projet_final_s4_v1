# Démarche de réalisation du projet

Objectif : créer un système qui simule un opérateur de Mobile Money.

Le travail est réparti en deux parties :
- **4208** : mise en place du système (faite).
- **4209** : améliorations et finitions (à venir).

---

# Partie 4208 — Mise en place du système + coté opérateur

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

# Installation — que faire après un `git pull`

La base de données (fichier `writable/mobilemoney.db`) n'est **pas** incluse dans le dépôt (elle est ignorée par git). Elle doit être créée en local une seule fois.

Après avoir récupéré le projet (`git pull`), exécuter dans le terminal, depuis la racine du projet :

```
php spark migrate
php spark db:seed InitialDataSeeder
```

Cela va :
1. créer le fichier `writable/mobilemoney.db` avec les 5 tables (prefixes, types_operation, baremes, clients, transactions),
2. insérer les données de départ (préfixes 033/037, types dépôt/retrait/transfert, barèmes de frais).

Pour lancer le site :
```
php spark serve
```
Puis ouvrir `http://localhost:8080` dans le navigateur.

> Note : la base n'étant pas poussée, chaque personne repart avec une base vide (sans les clients/tests), mais avec la structure et les données de départ (seed). C'est normal.

---

# Partie 4209 (Espace client, fait) — Côté client

But : permettre à un client de se connecter et d'effectuer ses opérations, sans inscription préalable.

## 1. Connexion automatique
- Page de connexion `/client/login` : le client saisit simplement son numéro de téléphone.
- `/client/authentifier` vérifie que le numéro commence par un **préfixe valide** de l'opérateur (033, 037 via `PrefixeModel::validePrefixe`).
- Si le client n'existe pas encore, il est **créé automatiquement** (pas d'inscription).
- La session mémorise le numéro (`client_telephone`) via un helper `client_auth` (`app/Helpers/client_auth_helper.php`).
- `/client/deconnecter` déconnecte le client.

## 2. Espace client
Contrôleur `app/Controllers/Client.php` et vues dans `app/Views/client/` :
- `/client` : tableau de bord avec le **solde** et les 5 dernières opérations.
- `/client/operations` : effectuer une opération (dépôt, retrait, transfert).
- `/client/executer` : traite l'opération et met à jour le solde + enregistre la transaction (frais/gain calculés via `BaremeModel`).
- `/client/historique` : liste de **toutes les opérations** du client.

## 3. Règles métier (côté client)
- **Dépôt** : crédite le solde, sans frais (supposé automatique).
- **Retrait** : débite solde + frais, refusé si solde insuffisant (supposé automatique).
- **Transfert** : débite l'émetteur (montant + frais), crédite le destinataire, destinataire obligatoire et différent de soi.
- Les frais et le gain opérateur proviennent des **barèmes** configurés par l'opérateur (partie 4208).

## 4. Routes ajoutées (`app/Config/Routes.php`)
`client/login`, `client/authentifier`, `client/deconnecter`, `client`, `client/operations`, `client/executer`, `client/historique`.

## 5. Tests effectués
- Connexion d'un nouveau numéro → création automatique du client + accès à l'espace.
- Dépôt de 50 000 Ar → solde mis à jour.
- Transfert de 10 000 Ar vers un autre client → émetteur 39 900 (frais 100), destinataire 10 000.
- Historique correctement peuplé.

---

# Partie 4209 (Côté opérateur, fait) — Améliorations et finitions

## 1. Configuration des préfixes des autres opérateurs
- Migration `2024_01_02_000001_AddAutresOperateurs` : ajout de la colonne `prefixe.autre_operateur` (TINYINT, 0 = notre opérateur, 1 = autre opérateur).
- Le seeder `InitialDataSeeder` insère maintenant les préfixes tiers (032, 031, 034 en `autre_operateur = 1`) en plus de 033/037.
- `PrefixeModel` : `prefixePourNumero()`, `estAutreOperateur()`, `prefixesAutresOperateurs()`.
- Vue `prefixes/index` et `prefixes/form` : badge « Notre opérateur / Autre opérateur » + case à cocher.

## 2. Configuration du % de commission pour transfert vers un autre opérateur
- Nouvelle table `config_operateur` (clé/valeur) + `ConfigOperateurModel` (méthode `commissionAutreOperateur()`).
- Seeder : paramètre `commission_autre_operateur = 10` (% ).
- Contrôleur `Settings` + vue `settings/index` : page pour modifier le % (route `settings`, `settings/update`).

## 3. Application de la commission + séparation des gains
- `Operations::executer` et `Client::executer` : lors d'un transfert dont le destinataire est sur un préfixe `autre_operateur`,
  - calculent `commission_autre = montant * taux / 100`,
  - débitent `montant + frais + commission_autre`,
  - enregistrent `prefixe_dest_id` et `commission_autre` sur la transaction.
- Migration : colonnes `transactions.prefixe_dest_id` et `transactions.commission_autre`.
- Page `situation/gains` (`Situation::gains`) : sépare les gains « notre opérateur » vs « autres opérateurs » (commission supp. + gain).

## 4. Situation des montants à envoyer à chaque opérateur
- Page `situation/operateurs` (`Situation::operateurs`) : par préfixe tiers, nombre de transferts, montant total à envoyer (hors frais/commission), commission perçue.
- Liens ajoutés dans le menu (layout) : « Gains par opérateur », « Montants à envoyer », « Paramètres ».

## Tests effectués
- `php spark migrate` + `php spark db:seed InitialDataSeeder` : préfixes tiers et config insérés.
- Transfert de 50 000 Ar vers un client 032 → frais 400 + commission 5 000 (10 %) = gain 5 400 ; `prefixe_dest_id` renseigné.
- Pages `/situation/gains`, `/situation/operateurs`, `/settings` : rendues et correctes (séparation opérateur / autres opérateurs).
- MAJ du % via `/settings/update` : persisté en base.


