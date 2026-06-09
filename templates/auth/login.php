<div class="login-container">
  <div class="login-card">
    <div class="login-header text-center mb-4">
      <div class="login-logo">
        <i class="bi bi-capsule-pill"></i>
      </div>
      <h2>PharmaFEFO</h2>
      <p class="text-muted">Gestion de stock pharmaceutique</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle"></i> <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="index.php?route=auth/authenticate">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" class="form-control" id="email" name="email" required placeholder="votre@email.com">
        </div>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 btn-lg">
        <i class="bi bi-box-arrow-in-right"></i> Se connecter
      </button>
    </form>

    <div class="login-demo mt-4">
      <small class="text-muted d-block text-center mb-2">Comptes de démonstration :</small>
      <div class="demo-accounts">
        <small><strong>Admin:</strong> admin@pharmafefo.com / admin123</small>
        <small><strong>Préparateur:</strong> preparateur@pharmafefo.com / prep123</small>
        <small><strong>Pharmacien:</strong> pharmacien@pharmafefo.com / pharma123</small>
      </div>
    </div>
  </div>
</div>
