<div class="page-header">
  <h1><i class="bi bi-arrow-return-left"></i> Retours fournisseur</h1>
  <p class="text-muted">Gérer les retours de lots expirés ou défectueux</p>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-success"><?= e($message) ?></div>
<?php endif; ?>

<div class="row g-4">
  <!-- Create return form -->
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header"><h5 class="mb-0">Nouveau retour</h5></div>
      <div class="card-body">
        <form method="POST" action="index.php?route=stock/storeReturn">
          <div class="mb-3">
            <label class="form-label">Lot *</label>
            <select name="batch_id" class="form-select" required>
              <option value="">-- Sélectionner --</option>
              <?php foreach ($batches as $batch): ?>
              <option value="<?= $batch['id'] ?>">
                <?= e($batch['product_name']) ?> - <?= e($batch['batch_number']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantité *</label>
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

  <!-- Returns list -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header"><h5 class="mb-0">Historique des retours</h5></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Lot</th>
                <th>Qté</th>
                <th>Raison</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($returns)): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Aucun retour</td></tr>
              <?php else: ?>
              <?php foreach ($returns as $ret): ?>
              <tr>
                <td><?= e($ret['product_name']) ?></td>
                <td><code><?= e($ret['batch_number']) ?></code></td>
                <td><?= $ret['quantity'] ?></td>
                <td class="small"><?= e($ret['reason']) ?></td>
                <td>
                  <?php
                  $statusClass = match ($ret['status']) {
                    'APPROVED' => 'bg-success',
                    'REJECTED' => 'bg-danger',
                    default => 'bg-warning text-dark',
                  };
                  ?>
                  <span class="badge <?= $statusClass ?>"><?= e($ret['status']) ?></span>
                </td>
                <td>
                  <?php if ($ret['status'] === 'PENDING'): ?>
                  <form method="POST" action="index.php?route=stock/updateReturn" class="d-inline">
                    <input type="hidden" name="id" value="<?= $ret['id'] ?>">
                    <input type="hidden" name="status" value="APPROVED">
                    <button type="submit" class="btn btn-sm btn-success" title="Approuver"><i class="bi bi-check"></i></button>
                  </form>
                  <form method="POST" action="index.php?route=stock/updateReturn" class="d-inline">
                    <input type="hidden" name="id" value="<?= $ret['id'] ?>">
                    <input type="hidden" name="status" value="REJECTED">
                    <button type="submit" class="btn btn-sm btn-danger" title="Rejeter"><i class="bi bi-x"></i></button>
                  </form>
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
  </div>
</div>
