<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-archive"></i> Stock</h1>
    <p class="text-muted">Gestion des lots et inventaire</p>
  </div>
  <?php if (hasRole('PREPARATEUR', 'ADMIN')): ?>
  <div class="btn-group">
    <a href="index.php?route=stock/createBatch" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouveau lot</a>
    <a href="index.php?route=stock/entry" class="btn btn-success"><i class="bi bi-box-arrow-in-down"></i> Entrée</a>
    <a href="index.php?route=stock/exit" class="btn btn-warning"><i class="bi bi-box-arrow-up"></i> Sortie FEFO</a>
  </div>
  <?php endif; ?>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-dismissible fade show"><?= e($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= e($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Search and filters -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" action="index.php" class="row g-2 align-items-end">
      <input type="hidden" name="route" value="stock">
      <div class="col-md-5">
        <label class="form-label">Recherche</label>
        <input type="text" name="search" class="form-control" placeholder="Produit ou n° de lot..." value="<?= e($search ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Filtre</label>
        <select name="filter" class="form-select">
          <option value="">Tous les lots</option>
          <option value="critical" <?= ($filter ?? '') === 'critical' ? 'selected' : '' ?>>Critiques (&lt;30j)</option>
          <option value="warning" <?= ($filter ?? '') === 'warning' ? 'selected' : '' ?>>Attention (&lt;90j)</option>
          <option value="expired" <?= ($filter ?? '') === 'expired' ? 'selected' : '' ?>>Expirés</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
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
            <th>Produit</th>
            <th>N° Lot</th>
            <th>Quantité</th>
            <th>Expiration</th>
            <th>Jours restants</th>
            <th>Alerte</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($batches)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Aucun lot trouvé</td></tr>
          <?php else: ?>
          <?php foreach ($batches as $batch): ?>
          <?php $level = getAlertLevel($batch['expiry_date']); ?>
          <tr>
            <td class="fw-semibold"><?= e($batch['product_name']) ?></td>
            <td><code><?= e($batch['batch_number']) ?></code></td>
            <td><?= $batch['quantity'] ?></td>
            <td><?= formatDate($batch['expiry_date']) ?></td>
            <td><?= daysUntilExpiry($batch['expiry_date']) ?> j</td>
            <td><span class="badge <?= alertBadgeClass($level) ?>"><?= alertLevelLabel($level) ?></span></td>
            <td><span class="badge bg-secondary"><?= e($batch['status']) ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
