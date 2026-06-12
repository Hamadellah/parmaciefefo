<?php

class StockController
{
  private ProductRepository $productRepo;
  private StockBatchRepository $batchRepo;
  private StockMovementRepository $movementRepo;
  private AlertRepository $alertRepo;

  public function __construct()
  {
    $this->productRepo = new ProductRepository();
    $this->batchRepo = new StockBatchRepository();
    $this->movementRepo = new StockMovementRepository();
    $this->alertRepo = new AlertRepository();
  }

  /**
   * List all stock batches
   */
  public function index(): void
  {
    $search = $_GET['search'] ?? null;
    $filter = $_GET['filter'] ?? null;
    $batches = $this->batchRepo->findAll($search, $filter);
    $message = $_SESSION['flash_success'] ?? null;
    $error = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);

    view('stock/index', [
      'batches' => $batches,
      'search' => $search,
      'filter' => $filter,
      'message' => $message,
      'error' => $error,
    ]);
  }

  /**
   * Show create batch form (PREPARATEUR)
   */
  public function createBatch(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      $_SESSION['flash_error'] = 'Accès refusé.';
      redirect('stock');
    }

    $products = $this->productRepo->findAll();
    view('stock/create_batch', ['products' => $products]);
  }

  /**
   * Store new batch
   */
  public function storeBatch(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      redirect('stock');
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    $batchNumber = trim($_POST['batch_number'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $expiryDate = $_POST['expiry_date'] ?? '';

    if (!$productId || empty($batchNumber) || $quantity <= 0 || empty($expiryDate)) {
      $_SESSION['flash_error'] = 'Veuillez remplir tous les champs correctement.';
      redirect('stock/createBatch');
    }

    $batchId = $this->batchRepo->create($productId, $batchNumber, $quantity, $expiryDate);

    // Record IN movement for initial stock
    $userId = currentUser()['id'];
    $this->movementRepo->createIn($batchId, $userId, $quantity, 'Création du lot');

    // Generate alert if needed
    $level = getAlertLevel($expiryDate);
    if ($level !== 'green') {
      $product = $this->productRepo->findById($productId);
      $this->alertRepo->create($batchId, $level, 'Nouveau lot ' . $batchNumber . ' pour ' . $product['name']);
    }

    $_SESSION['flash_success'] = 'Lot créé avec succès.';
    redirect('stock');
  }

  /**
   * Show stock entry form
   */
  public function entry(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      redirect('stock');
    }

    $batches = $this->batchRepo->findAll();
    view('stock/entry', ['batches' => $batches]);
  }

  /**
   * Process stock entry
   */
  public function storeEntry(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      redirect('stock');
    }

    $batchId = (int) ($_POST['batch_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (!$batchId || $quantity <= 0) {
      $_SESSION['flash_error'] = 'Veuillez sélectionner un lot et une quantité valide.';
      redirect('stock/entry');
    }

    $result = $this->movementRepo->createEntry(
      $batchId,
      currentUser()['id'],
      $quantity,
      $notes,
      $this->batchRepo
    );

    if ($result['success']) {
      $_SESSION['flash_success'] = $result['message'];
    } else {
      $_SESSION['flash_error'] = $result['message'];
    }

    redirect('stock');
  }

  /**
   * Show stock exit form (FEFO applied automatically)
   */
  public function exit(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      redirect('stock');
    }

    $products = $this->productRepo->findAll();
    view('stock/exit', ['products' => $products]);
  }

  /**
   * Process stock exit with FEFO
   */
  public function storeExit(): void
  {
    if (!hasRole('PREPARATEUR', 'ADMIN')) {
      redirect('stock');
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (!$productId || $quantity <= 0) {
      $_SESSION['flash_error'] = 'Veuillez sélectionner un produit et une quantité valide.';
      redirect('stock/exit');
    }

    // FEFO: automatically select nearest expiry batch
    $result = $this->movementRepo->createOut(
      $productId,
      currentUser()['id'],
      $quantity,
      $notes,
      $this->batchRepo
    );

    if ($result['success']) {
      $_SESSION['flash_success'] = $result['message'];
    } else {
      $_SESSION['flash_error'] = $result['message'];
    }

    redirect('stock');
  }

  /**
   * List all movements
   */
  public function movements(): void
  {
    $type = $_GET['type'] ?? null;
    $movements = $this->movementRepo->findAll($type);
    view('stock/movements', ['movements' => $movements, 'type' => $type]);
  }

  /**
   * Preview FEFO batch for a product (AJAX helper via GET)
   */
  public function fefoPreview(): void
  {
    $productId = (int) ($_GET['product_id'] ?? 0);
    $batch = $this->batchRepo->getFEFOBatch($productId);

    header('Content-Type: application/json');
    if ($batch) {
      echo json_encode([
        'found' => true,
        'batch_number' => $batch['batch_number'],
        'expiry_date' => $batch['expiry_date'],
        'quantity' => $batch['quantity'],
        'level' => getAlertLevel($batch['expiry_date']),
      ]);
    } else {
      echo json_encode(['found' => false]);
    }
    exit;
  }

  /**
   * Validate inventory (PHARMACIEN) - mark expired batches
   */
  public function validateInventory(): void
  {
    if (!hasRole('PHARMACIEN', 'ADMIN')) {
      $_SESSION['flash_error'] = 'Accès refusé.';
      redirect('stock');
    }

    $expired = $this->batchRepo->markExpiredBatches();
    $alerts = $this->alertRepo->generateAlerts($this->batchRepo);

    $_SESSION['flash_success'] = "Inventaire validé. $expired lot(s) expiré(s) marqué(s), $alerts nouvelle(s) alerte(s).";
    redirect('stock');
  }

  /**
   * List supplier returns (PHARMACIEN)
   */
  public function returns(): void
  {
    if (!hasRole('PHARMACIEN', 'ADMIN')) {
      redirect('dashboard');
    }

    $returns = $this->batchRepo->findAllReturns();
    $batches = $this->batchRepo->findAll(null, 'expired');
    $message = $_SESSION['flash_success'] ?? null;
    unset($_SESSION['flash_success']);

    view('stock/returns', [
      'returns' => $returns,
      'batches' => $batches,
      'message' => $message,
    ]);
  }

  /**
   * Create supplier return
   */
  public function storeReturn(): void
  {
    if (!hasRole('PHARMACIEN', 'ADMIN')) {
      redirect('dashboard');
    }

    $batchId = (int) ($_POST['batch_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    if (!$batchId || $quantity <= 0 || empty($reason)) {
      $_SESSION['flash_error'] = 'Veuillez remplir tous les champs.';
      redirect('stock/returns');
    }

    $this->batchRepo->createReturn($batchId, $quantity, $reason);
    $_SESSION['flash_success'] = 'Retour fournisseur enregistré.';
    redirect('stock/returns');
  }

  /**
   * Approve or reject a return
   */
  public function updateReturn(): void
  {
    if (!hasRole('PHARMACIEN', 'ADMIN')) {
      redirect('dashboard');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'PENDING';

    $this->batchRepo->updateReturnStatus($id, $status);
    $_SESSION['flash_success'] = 'Statut du retour mis à jour.';
    redirect('stock/returns');
  }
}
