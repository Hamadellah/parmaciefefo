<?php

/**
 * UserRepository - all SQL queries for users
 */
class UserRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * Find user by email (for login)
   */
  public function findByEmail(string $email): ?array
  {
    $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();

    return $row ?: null;
  }

  /**
   * Find user by ID
   */
  public function findById(int $id): ?array
  {
    $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ?: null;
  }

  /**
   * Get all users
   */
  public function findAll(): array
  {
    $sql = 'SELECT id, name, email, role, created_at FROM users ORDER BY name ASC';
    $stmt = $this->db->query($sql);

    return $stmt->fetchAll();
  }

  /**
   * Create a new user
   */
  public function create(string $name, string $email, string $password, string $role): int
  {
    $sql = 'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'name' => $name,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'role' => $role,
    ]);

    return (int) $this->db->lastInsertId();
  }

  /**
   * Update an existing user
   */
  public function update(int $id, string $name, string $email, string $role, ?string $password = null): bool
  {
    if ($password) {
      $sql = 'UPDATE users SET name = :name, email = :email, role = :role, password = :password WHERE id = :id';
      $params = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'password' => password_hash($password, PASSWORD_DEFAULT),
      ];
    } else {
      $sql = 'UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id';
      $params = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'role' => $role,
      ];
    }

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }

  /**
   * Delete a user
   */
  public function delete(int $id): bool
  {
    $sql = 'DELETE FROM users WHERE id = :id';
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $id]);
  }

  /**
   * Count total users
   */
  public function count(): int
  {
    $sql = 'SELECT COUNT(*) FROM users';
    return (int) $this->db->query($sql)->fetchColumn();
  }
}
