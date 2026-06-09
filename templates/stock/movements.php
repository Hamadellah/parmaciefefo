<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h1><i class="bi bi-arrow-left-right"></i> Mouvements de stock</h1>
    <p class="text-muted">Historique des entrées et sorties</p>
  </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
  <div class="card-body py-2">
    <div class="btn-group">
      <a href="index.php?route=stock/movements" class="btn btn-sm <?= empty($type) ? 'btn-primary' : 'btn-outline-primary' ?>">Tous</a>
      <a href="index.php?route=stock/movements&type=IN" class="btn btn-sm <?= ($type ?? '') === 'IN' ? 'btn-success' : 'btn-outline-success' ?>">Entrées</a>
      <a href="index.php?route=stock/movements&type=OUT" class="btn btn-sm <?= ($type ?? '') === 'OUT' ? 'btn-warning' : 'btn-outline-warning' ?>">Sorties</a>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Produit</th>
            <th>Lot</th>
            <th>Quantité</th>
            <th>Utilisateur</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($movements)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Aucun mouvement</td></tr>
          <?php else: ?>
          <?php foreach ($movements as $mov): ?>
          <tr>
            <td><?= formatDate($mov['created_at']) ?></td>
            <td>
              <?php if ($mov['type'] === 'IN'): ?>
              <span class="badge bg-success">ENTRÉE</span>
              <?php else: ?>
              <span class="badge bg-warning text-dark">SORTIE</span>
              <?php endif; ?>
            </td>
            <td><?= e($mov['product_name']) ?></td>
            <td><code><?= e($mov['batch_number']) ?></code></td>
            <td><?= $mov['quantity'] ?></td>
            <td><?= e($mov['user_name']) ?></td>
            <td class="text-muted small"><?= e($mov['notes'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
