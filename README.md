# PharmaFEFO

Application web de gestion de stock pharmaceutique basée sur la règle **FEFO** (First Expired, First Out).

Lors d'une vente ou sortie de stock, le système sélectionne automatiquement le lot ayant la date d'expiration la plus proche.

## Technologies

- PHP 8+
- MySQL
- PDO (requêtes préparées)
- Architecture MVC simple
- Bootstrap 5

## Structure du projet

```
PharmaFEFO/
├── config/
│   └── database.php          # Configuration PDO
├── database/
│   ├── schema.sql            # Schéma MySQL
│   └── seed.sql              # Données de démonstration
├── public/
│   ├── index.php             # Front controller (point d'entrée)
│   ├── css/style.css
│   └── js/app.js
├── src/
│   ├── Controller/           # Contrôleurs MVC
│   ├── Entity/               # Entités (getters/setters)
│   ├── Repository/           # Couche d'accès aux données (SQL)
│   ├── Enum/                 # Énumérations (rôles, statuts)
│   └── helpers.php           # Fonctions utilitaires
└── templates/                # Vues (HTML uniquement)
```

## Installation (XAMPP)

### 1. Copier le projet

Placez le dossier dans `C:\xampp\htdocs\PharmaFEFO`

### 2. Créer la base de données

Ouvrez phpMyAdmin (`http://localhost/phpmyadmin`) et exécutez :

1. `database/schema.sql` — crée la base et les tables
2. `database/seed.sql` — insère les données de démonstration

Ou via la ligne de commande :

```bash
c:\xampp\mysql\bin\mysql.exe -u root < database/schema.sql
c:\xampp\mysql\bin\mysql.exe -u root < database/seed.sql
```

### 3. Configurer la connexion

Modifiez `config/database.php` si nécessaire (par défaut : `root` sans mot de passe).

### 4. Accéder à l'application

```
http://localhost/PharmaFEFO/public/
```

## Comptes de démonstration

| Rôle         | Email                        | Mot de passe |
|--------------|------------------------------|--------------|
| Admin        | admin@pharmafefo.com         | admin123     |
| Préparateur  | preparateur@pharmafefo.com   | prep123      |
| Pharmacien   | pharmacien@pharmafefo.com    | pharma123    |

## Rôles et permissions

### Préparateur
- Connexion
- Créer des lots de stock
- Enregistrer les entrées de stock
- Enregistrer les sorties de stock (FEFO automatique)

### Pharmacien
- Voir les alertes d'expiration
- Filtrer les produits critiques
- Valider les inventaires
- Gérer les retours fournisseur

### Admin
- Gérer les utilisateurs
- Gérer les produits
- Générer les rapports de pertes
- Voir le tableau de bord

## Règle FEFO

La méthode `getFEFOBatch(productId)` dans `StockBatchRepository` retourne le lot avec :

- `quantity > 0`
- Non expiré (`expiry_date >= aujourd'hui`)
- Date d'expiration la plus proche (`ORDER BY expiry_date ASC LIMIT 1`)

Lors d'une sortie de stock, ce lot est automatiquement sélectionné et sa quantité est réduite.

## Niveaux d'alerte

| Couleur  | Condition              |
|----------|------------------------|
| Vert     | Plus de 6 mois (>180j) |
| Orange   | Moins de 90 jours      |
| Rouge    | Moins de 30 jours      |
| Expiré   | Date dépassée          |

## Architecture MVC

- **Controllers** : reçoivent les requêtes, appellent les repositories, chargent les vues
- **Repositories** : contiennent tout le SQL (PDO prepared statements)
- **Entities** : propriétés avec getters/setters uniquement
- **Templates** : HTML uniquement, pas de SQL

## Routage

Format : `index.php?route=controller/action`

Exemples :
- `index.php?route=dashboard`
- `index.php?route=stock/exit`
- `index.php?route=products/create`

## Licence

Projet éducatif — libre d'utilisation.
