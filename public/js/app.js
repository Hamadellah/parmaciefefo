/**
 * PharmaFEFO - Client-side JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

  // Sidebar toggle for mobile
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('show');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      if (window.innerWidth < 992) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
          sidebar.classList.remove('show');
        }
      }
    });
  }

  // FEFO preview when selecting a product on exit form
  const productSelect = document.getElementById('productSelect');
  const fefoPreview = document.getElementById('fefoPreview');
  const fefoDetails = document.getElementById('fefoDetails');

  if (productSelect && fefoPreview && fefoDetails) {
    productSelect.addEventListener('change', function () {
      const productId = this.value;

      if (!productId) {
        fefoPreview.style.display = 'none';
        return;
      }

      fetch('index.php?route=stock/fefoPreview&product_id=' + productId)
        .then(function (response) { return response.json(); })
        .then(function (data) {
          if (data.found) {
            const badgeClass = {
              green: 'bg-success',
              orange: 'bg-warning text-dark',
              red: 'bg-danger',
              expired: 'bg-dark'
            };

            fefoDetails.innerHTML =
              '<div class="d-flex align-items-center gap-3 flex-wrap">' +
              '<span><strong>Lot:</strong> <code>' + data.batch_number + '</code></span>' +
              '<span><strong>Expiration:</strong> ' + data.expiry_date + '</span>' +
              '<span><strong>Disponible:</strong> ' + data.quantity + '</span>' +
              '<span class="badge ' + (badgeClass[data.level] || 'bg-secondary') + '">' + data.level.toUpperCase() + '</span>' +
              '</div>';
            fefoPreview.style.display = 'block';
          } else {
            fefoDetails.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> Aucun lot disponible pour ce produit.</span>';
            fefoPreview.style.display = 'block';
          }
        })
        .catch(function () {
          fefoPreview.style.display = 'none';
        });
    });
  }

});
