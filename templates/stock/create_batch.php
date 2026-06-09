<div class="page-header">
  <h1><i class="bi bi-plus-circle"></i> Créer un lot de stock</h1>
  <p class="text-muted">Ajouter un nouveau lot pour un produit</p>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=stock/storeBatch">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Produit *</label>
          <select name="product_id" class="form-select" required>
            <option value="">-- Sélectionner un produit --</option>
            <?php foreach ($products as $product): ?>
            <option value="<?= $product['id'] ?>"><?= e($product['name']) ?> (<?= e($product['category']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Numéro de lot *</label>
          <input type="text" name="batch_number" class="form-control" required placeholder="ex: PARA-2026-001">
        </div>
        <div class="col-md-4">
          <label class="form-label">Quantité *</label>
          <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date d'expiration *</label>
          <input type="date" name="expiry_date" class="form-control" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Créer le lot</button>
          <a href="index.php?route=stock" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
