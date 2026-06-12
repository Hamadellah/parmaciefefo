<?php

/**
 * AlertRepository - all SQL queries for expiration alerts
 */
class AlertRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * Get all alerts with batch and product details
   */
  public function findAll(?string $level = null, ?bool $unreadOnly = false): array
  {
    $sql = 'SELECT a.*, sb.batch_number, sb.expiry_date, p.name AS product_name
            FROM alerts a
            JOIN stock_batches sb ON sb.id = a.batch_id
            JOIN products p ON p.id = sb.product_id
            WHERE 1=1';
    $params = [];

    if ($level) {
      $sql .= ' AND a.level = :level';
      $params['level'] = $level;
    }

    if ($unreadOnly) {
      $sql .= ' AND a.is_read = 0';
    }

    $sql .= ' ORDER BY FIELD(a.level, "expired", "red", "orange", "green"), a.created_at DESC';

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  /**
   * Create an alert for a batch
   */
  public function create(int $batchId, string $level, string $message): int
  {
    $sql = 'INSERT INTO alerts (batch_id, level, message, is_read) VALUES (:batch_id, :level, :message, 0)';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'batch_id' => $batchId,
      'level' => $level,
      'message' => $message,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Mark alert as read
   */
  public function markAsRead(int $id): bool
  {
    $sql = 'UPDATE alerts SET is_read = 1 WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $id]);
  }

  /**
   * Count unread alerts
   */
  public function countUnread(): int
  {
    $sql = 'SELECT COUNT(*) FROM alerts WHERE is_read = 0';
    return (int) $this->db->query($sql)->fetchColumn();
  }

  /**
   * Generate alerts for all active batches based on expiry dates
   */
  public function generateAlerts(StockBatchRepository $batchRepo): int
  {
    $batches = $batchRepo->findAll();
    $count = 0;

    foreach ($batches as $batch) {
      if ($batch['quantity'] <= 0) {
        continue;
      }

      $level = getAlertLevel($batch['expiry_date']);

      // Only create alerts for non-green levels
      if ($level === 'green') {
        continue;
      }

      // Check if alert already exists for this batch and level
      $sql = 'SELECT id FROM alerts WHERE batch_id = :batch_id AND level = :level AND is_read = 0 LIMIT 1';
      $stmt = $this->db->prepare($sql);
      $stmt->execute(['batch_id' => $batch['id'], 'level' => $level]);

      if (!$stmt->fetch()) {
        $daysLeft = daysUntilExpiry($batch['expiry_date']);
        $message = match ($level) {
          'expired' => 'Lot ' . $batch['batch_number'] . ' expiré - action requise',
          'red' => $batch['product_name'] . ' lot ' . $batch['batch_number'] . ' expire dans ' . $daysLeft . ' jours',
          'orange' => $batch['product_name'] . ' lot ' . $batch['batch_number'] . ' expire dans ' . $daysLeft . ' jours',
          default => 'Alerte pour lot ' . $batch['batch_number'],
        };

        $this->create($batch['id'], $level, $message);
        $count++;
      }
    }

    return $count;
  }

  /**
   * Count alerts by level
   */
  public function countByLevel(string $level): int
  {
    $sql = 'SELECT COUNT(*) FROM alerts WHERE level = :level AND is_read = 0';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['level' => $level]);

    return (int) $stmt->fetchColumn();
  }
}
