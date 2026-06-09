<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h1><i class="bi bi-bell"></i> Alertes d'expiration</h1>
    <p class="text-muted">Surveillance des dates de péremption</p>
  </div>
  <a href="index.php?route=alerts/markAllRead" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-check-all"></i> Tout marquer comme lu
  </a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-success"><?= e($message) ?></div>
<?php endif; ?>

<!-- Alert legend -->
<div class="alert-legend mb-4">
  <span class="badge bg-success">Vert : +6 mois</span>
  <span class="badge bg-warning text-dark">Orange : &lt;90 jours</span>
  <span class="badge bg-danger">Rouge : &lt;30 jours</span>
  <span class="badge bg-dark">Expiré</span>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
  <div class="card-body py-2">
    <div class="btn-group flex-wrap">
      <a href="index.php?route=alerts" class="btn btn-sm <?= empty($level) && empty($criticalOnly) ? 'btn-primary' : 'btn-outline-primary' ?>">Toutes</a>
      <a href="index.php?route=alerts&critical=1" class="btn btn-sm <?= !empty($criticalOnly) ? 'btn-danger' : 'btn-outline-danger' ?>">Critiques</a>
      <a href="index.php?route=alerts&level=expired" class="btn btn-sm <?= ($level ?? '') === 'expired' ? 'btn-dark' : 'btn-outline-dark' ?>">Expirés</a>
      <a href="index.php?route=alerts&level=red" class="btn btn-sm <?= ($level ?? '') === 'red' ? 'btn-danger' : 'btn-outline-danger' ?>">Rouge</a>
      <a href="index.php?route=alerts&level=orange" class="btn btn-sm <?= ($level ?? '') === 'orange' ? 'btn-warning' : 'btn-outline-warning' ?>">Orange</a>
      <a href="index.php?route=alerts&level=green" class="btn btn-sm <?= ($level ?? '') === 'green' ? 'btn-success' : 'btn-outline-success' ?>">Vert</a>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Niveau</th>
            <th>Produit</th>
            <th>Lot</th>
            <th>Expiration</th>
            <th>Message</th>
            <th>Lu</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($alerts)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Aucune alerte</td></tr>
          <?php else: ?>
          <?php foreach ($alerts as $alert): ?>
          <tr class="<?= $alert['is_read'] ? '' : 'table-warning' ?>">
            <td><span class="badge <?= alertBadgeClass($alert['level']) ?>"><?= alertLevelLabel($alert['level']) ?></span></td>
            <td class="fw-semibold"><?= e($alert['product_name']) ?></td>
            <td><code><?= e($alert['batch_number']) ?></code></td>
            <td><?= formatDate($alert['expiry_date']) ?></td>
            <td class="small"><?= e($alert['message']) ?></td>
            <td>
              <?php if ($alert['is_read']): ?>
              <i class="bi bi-check-circle text-success"></i>
              <?php else: ?>
              <i class="bi bi-circle text-warning"></i>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!$alert['is_read']): ?>
              <a href="index.php?route=alerts/markRead&id=<?= $alert['id'] ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-check"></i> Lu
              </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
