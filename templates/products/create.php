<div class="page-header">
  <h1><i class="bi bi-plus-circle"></i> Nouveau produit</h1>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="index.php?route=products/store">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom du produit *</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Catégorie</label>
          <input type="text" name="category" class="form-control" value="General">
        </div>
        <div class="col-md-3">
          <label class="form-label">Unité</label>
          <input type="text" name="unit" class="form-control" value="unité">
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Créer</button>
          <a href="index.php?route=products" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
