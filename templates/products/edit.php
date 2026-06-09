<div class="page-header">
  <h1><i class="bi bi-pencil"></i> Modifier le produit</h1>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=products/update">
      <input type="hidden" name="id" value="<?= $product['id'] ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom du produit *</label>
          <input type="text" name="name" class="form-control" value="<?= e($product['name']) ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Catégorie</label>
          <input type="text" name="category" class="form-control" value="<?= e($product['category']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Unité</label>
          <input type="text" name="unit" class="form-control" value="<?= e($product['unit']) ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"><?= e($product['description'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer</button>
          <a href="index.php?route=products" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
