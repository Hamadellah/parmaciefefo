<?php

/**
 * ProductController - manage products (ADMIN only)
 */
class ProductController
{
  private ProductRepository $productRepo;

  public function __construct()
  {
    $this->productRepo = new ProductRepository();
  }

  /**
   * List all products
   */
  public function index(): void
  {
    if (!hasRole('ADMIN')) {
      $_SESSION['flash_error'] = 'Accès refusé.';
      redirect('dashboard');
    }

    $search = $_GET['search'] ?? null;
    $products = $this->productRepo->findAll($search);
    $message = $_SESSION['flash_success'] ?? null;
    $error = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);

    view('products/index', [
      'products' => $products,
      'search' => $search,
      'message' => $message,
      'error' => $error,
    ]);
  }

  /**
   * Show create product form
   */
  public function create(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    view('products/create');
  }

  /**
   * Store new product
   */
  public function store(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $unit = trim($_POST['unit'] ?? 'unité');

    if (empty($name)) {
      $_SESSION['flash_error'] = 'Le nom du produit est obligatoire.';
      redirect('products/create');
    }

    $this->productRepo->create($name, $description, $category, $unit);
    $_SESSION['flash_success'] = 'Produit créé avec succès.';
    redirect('products');
  }

  /**
   * Show edit product form
   */
  public function edit(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_GET['id'] ?? 0);
    $product = $this->productRepo->findById($id);

    if (!$product) {
      $_SESSION['flash_error'] = 'Produit introuvable.';
      redirect('products');
    }

    view('products/edit', ['product' => $product]);
  }

  /**
   * Update product
   */
  public function update(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $unit = trim($_POST['unit'] ?? 'unité');

    if (empty($name)) {
      $_SESSION['flash_error'] = 'Le nom du produit est obligatoire.';
      redirect('products/edit&id=' . $id);
    }

    $this->productRepo->update($id, $name, $description, $category, $unit);
    $_SESSION['flash_success'] = 'Produit mis à jour.';
    redirect('products');
  }

  /**
   * Delete product
   */
  public function delete(): void
  {
    if (!hasRole('ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_GET['id'] ?? 0);
    $this->productRepo->delete($id);
    $_SESSION['flash_success'] = 'Produit supprimé.';
    redirect('products');
  }
}
