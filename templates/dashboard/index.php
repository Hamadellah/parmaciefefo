<div class="page-header">
  <h1><i class="bi bi-speedometer2"></i> Tableau de bord</h1>
  <p class="text-muted">Vue d'ensemble de votre stock pharmaceutique</p>
</div>

<!-- Statistics cards -->
<div class="row g-4 mb-4">
  <div class="col-md-6 col-xl">
    <div class="stat-card stat-blue">
      <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
      <div class="stat-info">
        <h3><?= $stats['totalProducts'] ?></h3>
        <p>Produits</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl">
    <div class="stat-card stat-green">
      <div class="stat-icon"><i class="bi bi-archive"></i></div>
      <div class="stat-info">
        <h3><?= $stats['totalBatches'] ?></h3>
        <p>Lots en stock</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl">
    <div class="stat-card stat-red">
      <div class="stat-icon"><i class="bi bi-exclamation-octagon"></i></div>
      <div class="stat-info">
        <h3><?= $stats['expiredBatches'] ?></h3>
        <p>Lots expirés</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl">
    <div class="stat-card stat-orange">
      <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
      <div class="stat-info">
        <h3><?= $stats['criticalBatches'] ?></h3>
        <p>Lots critiques (&lt;30j)</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl">
    <div class="stat-card stat-purple">
      <div class="stat-icon"><i class="bi bi-graph-down"></i></div>
      <div class="stat-info">
        <h3><?= $stats['monthlyLosses'] ?></h3>
        <p>Pertes ce mois</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Critical batches -->
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-warning"></i> Lots critiques</h5>
        <a href="index.php?route=alerts&critical=1" class="btn btn-sm btn-outline-primary">Voir tout</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Lot</th>
                <th>Expiration</th>
                <th>Qté</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($criticalBatches)): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Aucun lot critique</td></tr>
              <?php else: ?>
              <?php foreach ($criticalBatches as $batch): ?>
              <?php $level = getAlertLevel($batch['expiry_date']); ?>
              <tr>
                <td><?= e($batch['product_name']) ?></td>
                <td><code><?= e($batch['batch_number']) ?></code></td>
                <td><?= formatDate($batch['expiry_date']) ?></td>
                <td><?= $batch['quantity'] ?></td>
                <td><span class="badge <?= alertBadgeClass($level) ?>"><?= alertLevelLabel($level) ?></span></td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent alerts -->
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-bell text-danger"></i> Alertes récentes</h5>
        <span class="badge bg-danger"><?= $stats['unreadAlerts'] ?> non lues</span>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php if (empty($recentAlerts)): ?>
          <li class="list-group-item text-center text-muted py-4">Aucune alerte</li>
          <?php else: ?>
          <?php foreach ($recentAlerts as $alert): ?>
          <li class="list-group-item">
            <div class="d-flex align-items-start">
              <span class="badge <?= alertBadgeClass($alert['level']) ?> me-2 mt-1">&nbsp;</span>
              <div>
                <small class="fw-semibold"><?= e($alert['product_name']) ?></small>
                <p class="mb-0 small text-muted"><?= e($alert['message']) ?></p>
              </div>
            </div>
          </li>
          <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php if (hasRole('ADMIN') && !empty($lossReports)): ?>
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph"></i> Pertes du mois</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Lot</th>
                <th>Quantité</th>
                <th>Raison</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lossReports as $report): ?>
              <tr>
                <td><?= e($report['product_name']) ?></td>
                <td><code><?= e($report['batch_number']) ?></code></td>
                <td><?= $report['quantity'] ?></td>
                <td><?= e($report['reason']) ?></td>
                <td><?= formatDate($report['created_at']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
