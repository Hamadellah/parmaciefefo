<?php

/**
 * UserController - manage users (ADMIN only)
 */
class UserController
{
  private UserRepository $userRepo;
  private StockBatchRepository $batchRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
    $this->batchRepo = new StockBatchRepository();
  }

  /**
   * List all users
   */
  public function index(): void
  {
    if (!hasRole('ADMIN')) {
      $_SESSION['flash_error'] = 'Accès refusé.';
      redirect('dashboard');
    }

    $users = $this->userRepo->findAll();
    $message = $_SESSION['flash_success'] ?? null;
    $error = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);

    view('users/index', [
      'users' => $users,
      'message' => $message,
      'error' => $error,
    ]);
  }

  /**
   * Show create user form
   */
  public function create(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    view('users/create');
  }

  /**
   * Store new user
   */
  public function store(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'PREPARATEUR';

    if (empty($name) || empty($email) || empty($password)) {
      $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
      redirect('users/create');
    }

    $existing = $this->userRepo->findByEmail($email);
    if ($existing) {
      $_SESSION['flash_error'] = 'Cet email est déjà utilisé.';
      redirect('users/create');
    }

    $this->userRepo->create($name, $email, $password, $role);
    $_SESSION['flash_success'] = 'Utilisateur créé avec succès.';
    redirect('users');
  }

  /**
   * Show edit user form
   */
  public function edit(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_GET['id'] ?? 0);
    $user = $this->userRepo->findById($id);

    if (!$user) {
      $_SESSION['flash_error'] = 'Utilisateur introuvable.';
      redirect('users');
    }

    view('users/edit', ['user' => $user]);
  }

  /**
   * Update user
   */
  public function update(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'PREPARATEUR';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email)) {
      $_SESSION['flash_error'] = 'Le nom et l\'email sont obligatoires.';
      redirect('users/edit&id=' . $id);
    }

    $this->userRepo->update($id, $name, $email, $role, $password ?: null);
    $_SESSION['flash_success'] = 'Utilisateur mis à jour.';
    redirect('users');
  }

  /**
   * Delete user
   */
  public function delete(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_GET['id'] ?? 0);

    // Prevent deleting yourself
    if ($id === currentUser()['id']) {
      $_SESSION['flash_error'] = 'Vous ne pouvez pas supprimer votre propre compte.';
      redirect('users');
    }

    $this->userRepo->delete($id);
    $_SESSION['flash_success'] = 'Utilisateur supprimé.';
    redirect('users');
  }

  /**
   * Loss reports page (ADMIN)
   */
  public function lossReports(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $month = $_GET['month'] ?? date('Y-m');
    $reports = $this->batchRepo->findAllLossReports($month);
    $totalLoss = $this->batchRepo->getMonthlyLossTotal($month);

    view('users/loss_reports', [
      'reports' => $reports,
      'month' => $month,
      'totalLoss' => $totalLoss,
    ]);
  }

  /**
   * Create loss report (ADMIN)
   */
  public function storeLossReport(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $batchId = (int) ($_POST['batch_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    if (!$batchId || $quantity <= 0 || empty($reason)) {
      $_SESSION['flash_error'] = 'Veuillez remplir tous les champs.';
      redirect('users/lossReports');
    }

    $this->batchRepo->createLossReport($batchId, $quantity, $reason, currentUser()['id']);
    $_SESSION['flash_success'] = 'Rapport de perte enregistré.';
    redirect('users/lossReports');
  }
}
