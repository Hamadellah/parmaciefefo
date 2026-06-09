<?php

/**
 * AuthController - handles login and logout
 */
class AuthController
{
  private UserRepository $userRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
  }

  /**
   * Show login page
   */
  public function login(): void
  {
    if (isLoggedIn()) {
      redirect('dashboard');
    }

    $error = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_error']);

    view('auth/login', ['error' => $error]);
  }

  /**
   * Process login form
   */
  public function authenticate(): void
  {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
      $_SESSION['flash_error'] = 'Veuillez remplir tous les champs.';
      redirect('auth/login');
    }

    $user = $this->userRepo->findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
      $_SESSION['flash_error'] = 'Email ou mot de passe incorrect.';
      redirect('auth/login');
    }

    // Store user in session (without password)
    $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'role' => $user['role'],
    ];

    redirect('dashboard');
  }

  /**
   * Logout user
   */
  public function logout(): void
  {
    session_destroy();
    header('Location: index.php?route=auth/login');
    exit;
  }
}
