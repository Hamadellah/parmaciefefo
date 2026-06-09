<div class="page-header">
  <h1><i class="bi bi-person-plus"></i> Nouvel utilisateur</h1>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=users/store">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom *</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Mot de passe *</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="col-md-6">
          <label class="form-label">Rôle *</label>
          <select name="role" class="form-select" required>
            <option value="PREPARATEUR">Préparateur</option>
            <option value="PHARMACIEN">Pharmacien</option>
            <option value="ADMIN">Administrateur</option>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Créer</button>
          <a href="index.php?route=users" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
