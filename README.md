# PharmaFEFO

## 1. Présentation du projet

**PharmaFEFO** est une application web de gestion de stock pharmaceutique développée en **PHP** selon une architecture **MVC**. Elle permet de gérer les médicaments, les lots, les dates de péremption et les mouvements de stock en appliquant automatiquement la méthode **FEFO (First Expired, First Out)**. L'application s'adresse aux pharmacies afin d'optimiser la gestion des stocks, réduire les pertes liées aux péremptions et améliorer la traçabilité des médicaments.

---

# 2. Problématique

La gestion des stocks de médicaments est complexe, notamment lorsque plusieurs lots d'un même produit possèdent des dates de péremption différentes. Une mauvaise gestion peut entraîner des pertes financières, du gaspillage et des risques pour les patients.

PharmaFEFO répond à ce besoin en automatisant la sélection du lot à utiliser selon la règle **FEFO**, tout en proposant un suivi des dates de péremption, des alertes et une gestion complète des stocks.

---

# 3. Fonctionnalités principales

- Authentification avec gestion des rôles (Administrateur, Pharmacien, Préparateur)
- Gérer les médicaments
- Gérer les lots et les dates de péremption
- Enregistrer les entrées et les sorties de stock
- Appliquer automatiquement la méthode FEFO lors des sorties de stock
- Afficher les alertes des médicaments proches de la péremption
- Générer des rapports de pertes
- Consulter un tableau de bord de gestion

---

# 4. Technologies utilisées

| Technologie | Utilisation |
|-------------|-------------|
| PHP 8 | Développement de la logique métier |
| MySQL | Gestion de la base de données |
| PDO | Exécution sécurisée des requêtes SQL |
| HTML5 | Structure des pages |
| Bootstrap 5 | Interface utilisateur responsive |
| Architecture MVC | Organisation du projet |
| Git & GitHub | Gestion des versions |

---

# 5. Installation et lancement

## Prérequis

- PHP 8+
- MySQL
- XAMPP
- Git
- Navigateur Web

---

## Cloner le dépôt

```bash
git clone https://github.com/votre-compte/PharmaFEFO.git
```

---

## Ouvrir le dossier

```bash
cd PharmaFEFO
```

---

## Configuration

1. Copier le projet dans :

```text
C:\xampp\htdocs\PharmaFEFO
```

2. Ouvrir **phpMyAdmin**

3. Importer :

```
database/schema.sql
database/seed.sql
```

4. Vérifier les informations de connexion dans :

```
config/database.php
```

---

## Lancer le projet

Démarrer Apache et MySQL avec XAMPP puis ouvrir :

```
http://localhost/PharmaFEFO/public/
```

---

# 6. Structure du projet

```
PharmaFEFO
│
├── config/
├── database/
├── public/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   ├── Enum/
│   └── helpers.php
└── templates/
```

---

# 7. Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@pharmafefo.com | admin123 |
| Préparateur | preparateur@pharmafefo.com | prep123 |
| Pharmacien | pharmacien@pharmafefo.com | pharma123 |

---

# 8. Rôles et permissions

## Préparateur

- Se connecter
- Ajouter des lots
- Enregistrer les entrées de stock
- Effectuer les sorties de stock avec FEFO

## Pharmacien

- Consulter les alertes de péremption
- Valider les inventaires
- Gérer les retours fournisseur

## Administrateur

- Gérer les utilisateurs
- Gérer les médicaments
- Générer les rapports
- Consulter le tableau de bord

---

# 9. Fonctionnement de la méthode FEFO

Lorsqu'un médicament possède plusieurs lots disponibles, le système sélectionne automatiquement celui dont la date de péremption est la plus proche tout en restant valide.

Les conditions sont :

- quantité supérieure à zéro
- lot non expiré
- date de péremption la plus proche

Cette logique permet de limiter le gaspillage et de respecter la règle **First Expired, First Out (FEFO)**.

---

# 10. Niveaux d'alerte

| Niveau | Condition |
|---------|-----------|
| 🟢 Vert | Plus de 6 mois |
| 🟠 Orange | Moins de 90 jours |
| 🔴 Rouge | Moins de 30 jours |
| ⚫ Expiré | Date dépassée |

---

# 11. Contribution personnelle

J'ai réalisé l'intégralité du projet de manière autonome.

J'ai conçu la base de données, développé l'architecture MVC, implémenté la logique FEFO, développé les interfaces utilisateur, géré les rôles, les stocks, les alertes de péremption ainsi que les tests de fonctionnement.

---

# 12. Difficultés rencontrées

## Gestion automatique du FEFO

### Problème

Sélectionner automatiquement le bon lot lorsqu'un médicament possède plusieurs dates de péremption.

### Solution

Création d'une logique SQL permettant de récupérer le lot valide ayant la date d'expiration la plus proche avant d'effectuer la sortie de stock.

### Ce que j'ai appris

- Optimisation des requêtes SQL
- Gestion de la logique métier en PHP
- Application concrète de la méthode FEFO

---

## Gestion des rôles

### Problème

Sécuriser les différentes fonctionnalités selon le rôle de l'utilisateur.

### Solution

Mise en place d'une gestion des permissions pour l'Administrateur, le Pharmacien et le Préparateur.

### Ce que j'ai appris

- Gestion des autorisations
- Sécurisation d'une application PHP

---

# 13. Améliorations possibles

- Ajouter un lecteur de codes-barres
- Envoyer des alertes par e-mail
- Générer des statistiques avancées
- Déployer l'application en ligne
- Ajouter des tests automatisés

Ces améliorations permettraient d'améliorer les performances, la sécurité et l'expérience utilisateur.

---

# 14. Captures d'écran

## Tableau de bord

```md
![Dashboard](images/dashboard.png)
```

Le tableau de bord présente les informations principales sur le stock.

---

## Gestion des médicaments

```md
![Produits](images/products.png)
```

Cette capture montre la gestion des médicaments et des lots.

---

# 15. Licence

Projet réalisé dans un cadre pédagogique.

Libre d'utilisation à des fins d'apprentissage.
