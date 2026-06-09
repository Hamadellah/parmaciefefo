<div class="page-header">
  <h1><i class="bi bi-file-earmark-bar-graph"></i> Rapports de pertes</h1>
  <p class="text-muted">Suivi des pertes de stock</p>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="stat-card stat-purple">
      <div class="stat-icon"><i class="bi bi-graph-down"></i></div>
      <div class="stat-info">
        <h3><?= $totalLoss ?></h3>
        <p>Unités perdues en <?= e($month) ?></p>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header"><h5 class="mb-0">Nouveau rapport</h5></div>
      <div class="card-body">
        <form method="POST" action="index.php?route=users/storeLossReport">
          <div class="mb-3">
            <label class="form-label">ID du lot *</label>
            <input type="number" name="batch_id" class="form-control" required placeholder="ID du lot">
          </div>
          <div class="mb-3">
            <label class="form-label">Quantité perdue *</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Raison *</label>
            <textarea name="reason" class="form-control" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Enregistrer</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Historique</h5>
        <form method="GET" action="index.php" class="d-flex gap-2">
          <input type="hidden" name="route" value="users/lossReports">
          <input type="month" name="month" class="form-control form-control-sm" value="<?= e($month) ?>">
          <button type="submit" class="btn btn-sm btn-outline-primary">Filtrer</button>
        </form>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Lot</th>
                <th>Quantité</th>
                <th>Raison</th>
                <th>Rapporté par</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($reports)): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Aucun rapport pour cette période</td></tr>
              <?php else: ?>
              <?php foreach ($reports as $report): ?>
              <tr>
                <td><?= formatDate($report['created_at']) ?></td>
                <td><?= e($report['product_name']) ?></td>
                <td><code><?= e($report['batch_number']) ?></code></td>
                <td><span class="badge bg-danger"><?= $report['quantity'] ?></span></td>
                <td class="small"><?= e($report['reason']) ?></td>
                <td><?= e($report['reporter_name']) ?></td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
