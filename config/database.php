<?php

/**
 * Database configuration and PDO connection
 * Simple singleton pattern for database access
 */

class Database
{
  private static ?PDO $instance = null;

  // Database connection settings (XAMPP defaults)
  private static string $host = 'localhost';
  private static string $dbname = 'pharmafefo';
  private static string $username = 'root';
  private static string $password = '123456';

  /**
   * Get PDO database connection (creates one if needed)
   */
  public static function getConnection(): PDO
  {
    if (self::$instance === null) {
      $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';

      self::$instance = new PDO($dsn, self::$username, self::$password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);
    }

    return self::$instance;
  }
}
