<?php


class ProductRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * Get all products with optional search
   */
  public function findAll(?string $search = null): array
  {
    if ($search) {
      $sql = 'SELECT * FROM products
              WHERE name LIKE :search OR category LIKE :search OR description LIKE :search
              ORDER BY name ASC';
      $stmt = $this->db->prepare($sql);
      $stmt->execute(['search' => '%' . $search . '%']);
    } else {
      $sql = 'SELECT * FROM products ORDER BY name ASC';
      $stmt = $this->db->query($sql);
    }

    return $stmt->fetchAll();
  }

  /**
   * Find product by ID
   */
  public function findById(int $id): ?array
  {
    $sql = 'SELECT * FROM products WHERE id = :id LIMIT 1';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ?: null;
  }

  /**
   * Create a new product
   */
  public function create(string $name, string $description, string $category, string $unit): int
  {
    $sql = 'INSERT INTO products (name, description, category, unit) VALUES (:name, :description, :category, :unit)';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'name' => $name,
      'description' => $description,
      'category' => $category,
      'unit' => $unit,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Update a product
   */
  public function update(int $id, string $name, string $description, string $category, string $unit): bool
  {
    $sql = 'UPDATE products SET name = :name, description = :description, category = :category, unit = :unit WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
      'id' => $id,
      'name' => $name,
      'description' => $description,
      'category' => $category,
      'unit' => $unit,
    ]);
  }

  /**
   * Delete a product
   */
  public function delete(int $id): bool
  {
    $sql = 'DELETE FROM products WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $id]);
  }

  /**
   * Count total products
   */
  public function count(): int
  {
    $sql = 'SELECT COUNT(*) FROM products';
    return (int) $this->db->query($sql)->fetchColumn();
  }
}
