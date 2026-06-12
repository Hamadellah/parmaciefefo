<?php


class AlertController
{
  private AlertRepository $alertRepo;
  private StockBatchRepository $batchRepo;

  public function __construct()
  {
    $this->alertRepo = new AlertRepository();
    $this->batchRepo = new StockBatchRepository();
  }

  /**
   * List all alerts with filters
   */
  public function index(): void
  {
    if (!hasRole('PHARMACIEN', 'ADMIN', 'PREPARATEUR')) {
      redirect('dashboard');
    }

    // Refresh alerts
    $this->batchRepo->markExpiredBatches();
    $this->alertRepo->generateAlerts($this->batchRepo);

    $level = $_GET['level'] ?? null;
    $criticalOnly = isset($_GET['critical']);

    if ($criticalOnly) {
      $level = null;
      $alerts = array_filter($this->alertRepo->findAll(), function ($alert) {
        return in_array($alert['level'], ['red', 'expired', 'orange'], true);
      });
    } else {
      $alerts = $this->alertRepo->findAll($level);
    }

    $message = $_SESSION['flash_success'] ?? null;
    unset($_SESSION['flash_success']);

    view('alerts/index', [
      'alerts' => $alerts,
      'level' => $level,
      'criticalOnly' => $criticalOnly,
      'message' => $message,
    ]);
  }

  /**
   * Mark alert as read
   */
  public function markRead(): void
  {
    $id = (int) ($_GET['id'] ?? 0);
    $this->alertRepo->markAsRead($id);
    $_SESSION['flash_success'] = 'Alerte marquée comme lue.';
    redirect('alerts');
  }

  /**
   * Mark all alerts as read
   */
  public function markAllRead(): void
  {
    $alerts = $this->alertRepo->findAll(null, true);
    foreach ($alerts as $alert) {
      $this->alertRepo->markAsRead($alert['id']);
    }
    $_SESSION['flash_success'] = 'Toutes les alertes ont été marquées comme lues.';
    redirect('alerts');
  }
}
