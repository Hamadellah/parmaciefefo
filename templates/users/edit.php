<div class="page-header">
  <h1><i class="bi bi-pencil"></i> Modifier l'utilisateur</h1>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=users/update">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom *</label>
          <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
          <input type="password" name="password" class="form-control" minlength="6">
        </div>
        <div class="col-md-6">
          <label class="form-label">Rôle *</label>
          <select name="role" class="form-select" required>
            <option value="PREPARATEUR" <?= $user['role'] === 'PREPARATEUR' ? 'selected' : '' ?>>Préparateur</option>
            <option value="PHARMACIEN" <?= $user['role'] === 'PHARMACIEN' ? 'selected' : '' ?>>Pharmacien</option>
            <option value="ADMIN" <?= $user['role'] === 'ADMIN' ? 'selected' : '' ?>>Administrateur</option>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer</button>
          <a href="index.php?route=users" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
