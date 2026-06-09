<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h1><i class="bi bi-people"></i> Utilisateurs</h1>
    <p class="text-muted">Gérer les comptes utilisateurs</p>
  </div>
  <a href="index.php?route=users/create" class="btn btn-primary">
    <i class="bi bi-person-plus"></i> Nouvel utilisateur
  </a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-success alert-dismissible fade show"><?= e($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= e($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Créé le</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td class="fw-semibold"><?= e($user['name']) ?></td>
            <td><?= e($user['email']) ?></td>
            <td>
              <?php
              $roleBadge = match ($user['role']) {
                'ADMIN' => 'bg-danger',
                'PHARMACIEN' => 'bg-primary',
                default => 'bg-success',
              };
              ?>
              <span class="badge <?= $roleBadge ?>"><?= e($user['role']) ?></span>
            </td>
            <td><?= formatDate($user['created_at']) ?></td>
            <td>
              <a href="index.php?route=users/edit&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <?php if ($user['id'] !== currentUser()['id']): ?>
              <a href="index.php?route=users/delete&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Supprimer cet utilisateur ?')">
                <i class="bi bi-trash"></i>
              </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
