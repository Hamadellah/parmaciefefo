<?php

class StockMovementRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * Get all movements with details
   */
  public function findAll(?string $type = null): array
  {
    $sql = 'SELECT sm.*, sb.batch_number, p.name AS product_name, u.name AS user_name
            FROM stock_movements sm
            JOIN stock_batches sb ON sb.id = sm.batch_id
            JOIN products p ON p.id = sb.product_id
            JOIN users u ON u.id = sm.user_id
            WHERE 1=1';
    $params = [];

    if ($type) {
      $sql .= ' AND sm.type = :type';
      $params['type'] = $type;
    }

    $sql .= ' ORDER BY sm.created_at DESC';

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  /**
   * Create a stock IN movement (entry)
   */
  public function createIn(int $batchId, int $userId, int $quantity, string $notes = ''): int
  {
    $sql = 'INSERT INTO stock_movements (batch_id, user_id, type, quantity, notes)
            VALUES (:batch_id, :user_id, :type, :quantity, :notes)';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'batch_id' => $batchId,
      'user_id' => $userId,
      'type' => 'IN',
      'quantity' => $quantity,
      'notes' => $notes,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Create a stock OUT movement (exit) - uses FEFO batch automatically
   */
  public function createOut(int $productId, int $userId, int $quantity, string $notes, StockBatchRepository $batchRepo): array
  {
    // Get FEFO batch
    $batch = $batchRepo->getFEFOBatch($productId);

    if (!$batch) {
      return ['success' => false, 'message' => 'Aucun lot disponible pour ce produit (stock épuisé ou expiré).'];
    }

    if ($batch['quantity'] < $quantity) {
      return [
        'success' => false,
        'message' => 'Stock insuffisant dans le lot FEFO (' . $batch['batch_number'] . '). Disponible: ' . $batch['quantity'],
      ];
    }

    // Record the OUT movement
    $sql = 'INSERT INTO stock_movements (batch_id, user_id, type, quantity, notes)
            VALUES (:batch_id, :user_id, :type, :quantity, :notes)';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'batch_id' => $batch['id'],
      'user_id' => $userId,
      'type' => 'OUT',
      'quantity' => $quantity,
      'notes' => $notes,
    ]);

    // Reduce batch quantity
    $newQuantity = $batch['quantity'] - $quantity;
    $batchRepo->updateQuantity($batch['id'], $newQuantity);

    return [
      'success' => true,
      'message' => 'Sortie enregistrée. Lot FEFO utilisé: ' . $batch['batch_number'] . ' (exp: ' . $batch['expiry_date'] . ')',
      'batch_id' => $batch['id'],
      'movement_id' => (int) $this->db->lastInsertId(),
    ];
  }

  /**
   * Create IN movement and increase batch quantity
   */
  public function createEntry(int $batchId, int $userId, int $quantity, string $notes, StockBatchRepository $batchRepo): array
  {
    $batch = $batchRepo->findById($batchId);

    if (!$batch) {
      return ['success' => false, 'message' => 'Lot introuvable.'];
    }

    $this->createIn($batchId, $userId, $quantity, $notes);

    $newQuantity = $batch['quantity'] + $quantity;
    $batchRepo->updateQuantity($batchId, $newQuantity);

    return ['success' => true, 'message' => 'Entrée de stock enregistrée avec succès.'];
  }
}
