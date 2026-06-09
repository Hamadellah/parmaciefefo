<div class="page-header">
  <h1><i class="bi bi-box-arrow-in-down"></i> Entrée de stock</h1>
  <p class="text-muted">Enregistrer une réception de marchandise</p>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=stock/storeEntry">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Lot *</label>
          <select name="batch_id" class="form-select" required>
            <option value="">-- Sélectionner un lot --</option>
            <?php foreach ($batches as $batch): ?>
            <?php $level = getAlertLevel($batch['expiry_date']); ?>
            <option value="<?= $batch['id'] ?>">
              <?= e($batch['product_name']) ?> - <?= e($batch['batch_number']) ?> (exp: <?= formatDate($batch['expiry_date']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Quantité *</label>
          <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="2" placeholder="Référence bon de livraison, etc."></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Enregistrer l'entrée</button>
          <a href="index.php?route=stock" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
