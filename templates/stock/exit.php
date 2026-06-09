<div class="page-header">
  <h1><i class="bi bi-box-arrow-up"></i> Sortie de stock (FEFO)</h1>
  <p class="text-muted">Le système sélectionne automatiquement le lot avec la date d'expiration la plus proche</p>
</div>

<div class="alert alert-info">
  <i class="bi bi-info-circle"></i>
  <strong>Règle FEFO :</strong> First Expired, First Out — le lot expirant le plus tôt sera automatiquement utilisé.
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=stock/storeExit" id="exitForm">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Produit *</label>
          <select name="product_id" id="productSelect" class="form-select" required>
            <option value="">-- Sélectionner un produit --</option>
            <?php foreach ($products as $product): ?>
            <option value="<?= $product['id'] ?>"><?= e($product['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Quantité *</label>
          <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="2" placeholder="Vente, prescription, etc."></textarea>
        </div>

        <!-- FEFO preview -->
        <div class="col-12" id="fefoPreview" style="display:none;">
          <div class="fefo-preview-card">
            <h6><i class="bi bi-lightning"></i> Lot FEFO sélectionné automatiquement :</h6>
            <div id="fefoDetails"></div>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg"></i> Enregistrer la sortie</button>
          <a href="index.php?route=stock" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
