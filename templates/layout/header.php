<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PharmaFEFO - Gestion de Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php if (isLoggedIn()): ?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <i class="bi bi-capsule-pill"></i>
    <span>PharmaFEFO</span>
  </div>
  <nav class="sidebar-nav">
    <a href="index.php?route=dashboard" class="nav-link <?= ($currentRoute === 'dashboard') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Tableau de bord
    </a>

    <?php if (hasRole('ADMIN')): ?>
    <a href="index.php?route=products" class="nav-link <?= str_starts_with($currentRoute, 'products') ? 'active' : '' ?>">
      <i class="bi bi-box-seam"></i> Produits
    </a>
    <a href="index.php?route=users" class="nav-link <?= str_starts_with($currentRoute, 'users') ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Utilisateurs
    </a>
    <a href="index.php?route=users/lossReports" class="nav-link <?= ($currentRoute === 'users/lossReports') ? 'active' : '' ?>">
      <i class="bi bi-file-earmark-bar-graph"></i> Rapports de pertes
    </a>
    <?php endif; ?>

    <a href="index.php?route=stock" class="nav-link <?= ($currentRoute === 'stock' || str_starts_with($currentRoute, 'stock/')) ? 'active' : '' ?>">
      <i class="bi bi-archive"></i> Stock
    </a>

    <?php if (hasRole('PREPARATEUR', 'ADMIN')): ?>
    <a href="index.php?route=stock/createBatch" class="nav-link sub-link">
      <i class="bi bi-plus-circle"></i> Nouveau lot
    </a>
    <a href="index.php?route=stock/entry" class="nav-link sub-link">
      <i class="bi bi-box-arrow-in-down"></i> Entrée stock
    </a>
    <a href="index.php?route=stock/exit" class="nav-link sub-link">
      <i class="bi bi-box-arrow-up"></i> Sortie stock (FEFO)
    </a>
    <?php endif; ?>

    <a href="index.php?route=stock/movements" class="nav-link sub-link">
      <i class="bi bi-arrow-left-right"></i> Mouvements
    </a>

    <a href="index.php?route=alerts" class="nav-link <?= str_starts_with($currentRoute, 'alerts') ? 'active' : '' ?>">
      <i class="bi bi-bell"></i> Alertes
    </a>

    <?php if (hasRole('PHARMACIEN', 'ADMIN')): ?>
    <a href="index.php?route=stock/returns" class="nav-link sub-link">
      <i class="bi bi-arrow-return-left"></i> Retours fournisseur
    </a>
    <a href="index.php?route=stock/validateInventory" class="nav-link sub-link">
      <i class="bi bi-clipboard-check"></i> Valider inventaire
    </a>
    <?php endif; ?>
  </nav>
</aside>

<!-- Main content -->
<div class="main-wrapper">
  <!-- Top navbar -->
  <header class="top-navbar">
    <button class="btn btn-link sidebar-toggle" id="sidebarToggle">
      <i class="bi bi-list fs-4"></i>
    </button>
    <div class="navbar-title">
      <h5 class="mb-0">Gestion de Stock Pharmaceutique</h5>
      <small class="text-muted">Règle FEFO : First Expired, First Out</small>
    </div>
    <div class="navbar-user">
      <span class="badge bg-primary me-2"><?= e($currentUser['role'] ?? '') ?></span>
      <span class="me-3"><i class="bi bi-person-circle"></i> <?= e($currentUser['name'] ?? '') ?></span>
      <a href="index.php?route=auth/logout" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </header>

  <main class="content-area">
<?php else: ?>
<main class="auth-wrapper">
<?php endif; ?>
