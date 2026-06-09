<?php

/**
 * DashboardController - main dashboard with statistics
 */
class DashboardController
{
  private ProductRepository $productRepo;
  private StockBatchRepository $batchRepo;
  private AlertRepository $alertRepo;

  public function __construct()
  {
    $this->productRepo = new ProductRepository();
    $this->batchRepo = new StockBatchRepository();
    $this->alertRepo = new AlertRepository();
  }

  /**
   * Show dashboard with statistics
   */
  public function index(): void
  {
    // Update expired batches and generate alerts
    $this->batchRepo->markExpiredBatches();
    $this->alertRepo->generateAlerts($this->batchRepo);

    $stats = [
      'totalProducts' => $this->productRepo->count(),
      'totalBatches' => $this->batchRepo->count(),
      'expiredBatches' => $this->batchRepo->countExpired(),
      'criticalBatches' => $this->batchRepo->countCritical(),
      'monthlyLosses' => $this->batchRepo->getMonthlyLossTotal(),
      'unreadAlerts' => $this->alertRepo->countUnread(),
    ];

    // Recent critical batches for dashboard table
    $criticalBatches = $this->batchRepo->findAll(null, 'critical');
    $recentAlerts = $this->alertRepo->findAll(null, true);

    // Loss reports for admin
    $lossReports = [];
    if (hasRole('ADMIN')) {
      $lossReports = $this->batchRepo->findAllLossReports(date('Y-m'));
    }

    view('dashboard/index', [
      'stats' => $stats,
      'criticalBatches' => array_slice($criticalBatches, 0, 5),
      'recentAlerts' => array_slice($recentAlerts, 0, 5),
      'lossReports' => $lossReports,
    ]);
  }
}
