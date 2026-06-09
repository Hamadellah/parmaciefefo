<?php if (isLoggedIn()): ?>
  </main>
  <footer class="app-footer">
    <small>&copy; <?= date('Y') ?> PharmaFEFO - Gestion de stock pharmaceutique FEFO</small>
  </footer>
</div>
<?php else: ?>
</main>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
