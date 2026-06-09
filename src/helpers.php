<?php

/**
 * Helper functions used across the application
 */

/**
 * Render a view template with layout
 */
function view(string $template, array $data = []): void
{
  extract($data);
  $currentUser = $_SESSION['user'] ?? null;
  $currentRoute = $_GET['route'] ?? 'dashboard';

  require __DIR__ . '/../templates/layout/header.php';
  require __DIR__ . '/../templates/' . $template . '.php';
  require __DIR__ . '/../templates/layout/footer.php';
}

/**
 * Redirect to a route
 */
function redirect(string $route): void
{
  header('Location: index.php?route=' . $route);
  exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool
{
  return isset($_SESSION['user']);
}

/**
 * Get current logged-in user from session
 */
function currentUser(): ?array
{
  return $_SESSION['user'] ?? null;
}

/**
 * Check if current user has one of the given roles
 */
function hasRole(string ...$roles): bool
{
  $user = currentUser();
  if (!$user) {
    return false;
  }
  return in_array($user['role'], $roles, true);
}

/**
 * Calculate alert level based on expiry date
 * Green: more than 6 months (> 180 days)
 * Orange: less than 90 days
 * Red: less than 30 days
 * Expired: date has passed
 */
function getAlertLevel(string $expiryDate): string
{
  $today = new DateTime('today');
  $expiry = new DateTime($expiryDate);

  if ($expiry < $today) {
    return 'expired';
  }

  $daysLeft = (int) $today->diff($expiry)->days;

  if ($daysLeft < 30) {
    return 'red';
  }
  if ($daysLeft < 90) {
    return 'orange';
  }
  if ($daysLeft > 180) {
    return 'green';
  }

  // Between 90 and 180 days - normal status
  return 'green';
}

/**
 * Get Bootstrap badge class for alert level
 */
function alertBadgeClass(string $level): string
{
  return match ($level) {
    'green' => 'bg-success',
    'orange' => 'bg-warning text-dark',
    'red' => 'bg-danger',
    'expired' => 'bg-dark',
    default => 'bg-secondary',
  };
}

/**
 * Get human-readable label for alert level
 */
function alertLevelLabel(string $level): string
{
  return match ($level) {
    'green' => 'OK (+6 mois)',
    'orange' => 'Attention (<90j)',
    'red' => 'Critique (<30j)',
    'expired' => 'Expiré',
    default => 'Inconnu',
  };
}

/**
 * Escape HTML output (prevent XSS)
 */
function e(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format a date for display
 */
function formatDate(?string $date): string
{
  if (!$date) {
    return '-';
  }
  return date('d/m/Y', strtotime($date));
}

/**
 * Get days until expiry
 */
function daysUntilExpiry(string $expiryDate): int
{
  $today = new DateTime('today');
  $expiry = new DateTime($expiryDate);
  return (int) $today->diff($expiry)->format('%r%a');
}
