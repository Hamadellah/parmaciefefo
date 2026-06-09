<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h1><i class="bi bi-box-seam"></i> Produits</h1>
    <p class="text-muted">Gérer le catalogue de produits</p>
  </div>
  <a href="index.php?route=products/create" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Nouveau produit
  </a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-dismissible fade show">
  <?= e($message) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show">
  <?= e($error) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Search bar -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" action="index.php" class="row g-2">
      <input type="hidden" name="route" value="products">
      <div class="col-md-8">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un produit..." value="<?= e($search ?? '') ?>">
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-outline-primary me-2"><i class="bi bi-search"></i> Rechercher</button>
        <a href="index.php?route=products" class="btn btn-outline-secondary">Réinitialiser</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Unité</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Aucun produit trouvé</td></tr>
          <?php else: ?>
          <?php foreach ($products as $product): ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td class="fw-semibold"><?= e($product['name']) ?></td>
            <td><span class="badge bg-light text-dark"><?= e($product['category']) ?></span></td>
            <td><?= e($product['unit']) ?></td>
            <td class="text-muted small"><?= e(mb_substr($product['description'] ?? '', 0, 50)) ?></td>
            <td>
              <a href="index.php?route=products/edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="index.php?route=products/delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Supprimer ce produit ?')">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
