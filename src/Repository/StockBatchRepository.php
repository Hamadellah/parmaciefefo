<?php


class StockBatchRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * FEFO: Get the batch with nearest expiry date that has stock and is not expired
   */
  public function getFEFOBatch(int $productId): ?array
  {
    $sql = 'SELECT * FROM stock_batches
            WHERE product_id = :product_id
              AND quantity > 0
              AND expiry_date >= CURDATE()
              AND status = :status
            ORDER BY expiry_date ASC
            LIMIT 1';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'product_id' => $productId,
      'status' => 'ACTIVE',
    ]);
    $row = $stmt->fetch();

    return $row ?: null;
  }

  /**
   * Get all batches with product name
   */
  public function findAll(?string $search = null, ?string $filter = null): array
  {
    $sql = 'SELECT sb.*, p.name AS product_name
            FROM stock_batches sb
            JOIN products p ON p.id = sb.product_id
            WHERE 1=1';
    $params = [];

    if ($search) {
      $sql .= ' AND (p.name LIKE :search OR sb.batch_number LIKE :search)';
      $params['search'] = '%' . $search . '%';
    }

    // Filter by alert level
    if ($filter === 'expired') {
      $sql .= ' AND sb.expiry_date < CURDATE()';
    } elseif ($filter === 'critical') {
      $sql .= ' AND sb.expiry_date >= CURDATE() AND sb.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)';
    } elseif ($filter === 'warning') {
      $sql .= ' AND sb.expiry_date > DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND sb.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)';
    }

    $sql .= ' ORDER BY sb.expiry_date ASC';

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  /**
   * Get batches for a specific product
   */
  public function findByProductId(int $productId): array
  {
    $sql = 'SELECT sb.*, p.name AS product_name
            FROM stock_batches sb
            JOIN products p ON p.id = sb.product_id
            WHERE sb.product_id = :product_id
            ORDER BY sb.expiry_date ASC';

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['product_id' => $productId]);

    return $stmt->fetchAll();
  }

  /**
   * Find batch by ID
   */
  public function findById(int $id): ?array
  {
    $sql = 'SELECT sb.*, p.name AS product_name
            FROM stock_batches sb
            JOIN products p ON p.id = sb.product_id
            WHERE sb.id = :id LIMIT 1';

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ?: null;
  }

  /**
   * Create a new stock batch
   */
  public function create(int $productId, string $batchNumber, int $quantity, string $expiryDate): int
  {
    $status = (strtotime($expiryDate) < strtotime('today')) ? 'EXPIRED' : 'ACTIVE';

    $sql = 'INSERT INTO stock_batches (product_id, batch_number, quantity, expiry_date, status)
            VALUES (:product_id, :batch_number, :quantity, :expiry_date, :status)';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'product_id' => $productId,
      'batch_number' => $batchNumber,
      'quantity' => $quantity,
      'expiry_date' => $expiryDate,
      'status' => $status,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Update batch quantity (used after movements)
   */
  public function updateQuantity(int $id, int $newQuantity): bool
  {
    $status = 'ACTIVE';
    if ($newQuantity <= 0) {
      $newQuantity = 0;
      $status = 'DEPLETED';
    }

    $sql = 'UPDATE stock_batches SET quantity = :quantity, status = :status WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
      'id' => $id,
      'quantity' => $newQuantity,
      'status' => $status,
    ]);
  }

  /**
   * Mark expired batches automatically
   */
  public function markExpiredBatches(): int
  {
    $sql = 'UPDATE stock_batches SET status = :expired
            WHERE expiry_date < CURDATE() AND status = :active';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['expired' => 'EXPIRED', 'active' => 'ACTIVE']);

    return $stmt->rowCount();
  }

  /**
   * Count total batches
   */
  public function count(): int
  {
    $sql = 'SELECT COUNT(*) FROM stock_batches';
    return (int) $this->db->query($sql)->fetchColumn();
  }

  /**
   * Count expired batches
   */
  public function countExpired(): int
  {
    $sql = 'SELECT COUNT(*) FROM stock_batches WHERE expiry_date < CURDATE() OR status = :status';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['status' => 'EXPIRED']);

    return (int) $stmt->fetchColumn();
  }

  /**
   * Count critical batches (expire within 30 days)
   */
  public function countCritical(): int
  {
    $sql = 'SELECT COUNT(*) FROM stock_batches
            WHERE expiry_date >= CURDATE()
              AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
              AND quantity > 0';
    return (int) $this->db->query($sql)->fetchColumn();
  }

  // --- Supplier Returns ---

  /**
   * Get all supplier returns
   */
  public function findAllReturns(): array
  {
    $sql = 'SELECT r.*, sb.batch_number, p.name AS product_name
            FROM returns r
            JOIN stock_batches sb ON sb.id = r.batch_id
            JOIN products p ON p.id = sb.product_id
            ORDER BY r.created_at DESC';

    return $this->db->query($sql)->fetchAll();
  }

  /**
   * Create a supplier return
   */
  public function createReturn(int $batchId, int $quantity, string $reason): int
  {
    $sql = 'INSERT INTO returns (batch_id, quantity, reason, status) VALUES (:batch_id, :quantity, :reason, :status)';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'batch_id' => $batchId,
      'quantity' => $quantity,
      'reason' => $reason,
      'status' => 'PENDING',
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Update return status (approve/reject)
   */
  public function updateReturnStatus(int $id, string $status): bool
  {
    $sql = 'UPDATE returns SET status = :status WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $id, 'status' => $status]);
  }

  // --- Loss Reports ---

  /**
   * Get all loss reports
   */
  public function findAllLossReports(?string $month = null): array
  {
    $sql = 'SELECT lr.*, sb.batch_number, p.name AS product_name, u.name AS reporter_name
            FROM loss_reports lr
            JOIN stock_batches sb ON sb.id = lr.batch_id
            JOIN products p ON p.id = sb.product_id
            JOIN users u ON u.id = lr.reported_by
            WHERE 1=1';
    $params = [];

    if ($month) {
      $sql .= ' AND DATE_FORMAT(lr.created_at, "%Y-%m") = :month';
      $params['month'] = $month;
    }

    $sql .= ' ORDER BY lr.created_at DESC';

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  /**
   * Create a loss report
   */
  public function createLossReport(int $batchId, int $quantity, string $reason, int $reportedBy): int
  {
    $sql = 'INSERT INTO loss_reports (batch_id, quantity, reason, reported_by)
            VALUES (:batch_id, :quantity, :reason, :reported_by)';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'batch_id' => $batchId,
      'quantity' => $quantity,
      'reason' => $reason,
      'reported_by' => $reportedBy,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Get total monthly losses (quantity)
   */
  public function getMonthlyLossTotal(?string $month = null): int
  {
    if (!$month) {
      $month = date('Y-m');
    }

    $sql = 'SELECT COALESCE(SUM(quantity), 0) FROM loss_reports
            WHERE DATE_FORMAT(created_at, "%Y-%m") = :month';

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['month' => $month]);

    return (int) $stmt->fetchColumn();
  }
}
