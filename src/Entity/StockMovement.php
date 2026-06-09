<?php

/**
 * StockMovement entity - represents stock entry or exit
 */
class StockMovement
{
  private ?int $id = null;
  private int $batchId = 0;
  private int $userId = 0;
  private string $type = 'IN';
  private int $quantity = 0;
  private string $notes = '';
  private ?string $createdAt = null;

  // Extra joined fields
  private ?string $batchNumber = null;
  private ?string $productName = null;
  private ?string $userName = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function getBatchId(): int
  {
    return $this->batchId;
  }

  public function setBatchId(int $batchId): void
  {
    $this->batchId = $batchId;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }

  public function setUserId(int $userId): void
  {
    $this->userId = $userId;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): void
  {
    $this->type = $type;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): void
  {
    $this->quantity = $quantity;
  }

  public function getNotes(): string
  {
    return $this->notes;
  }

  public function setNotes(string $notes): void
  {
    $this->notes = $notes;
  }

  public function getCreatedAt(): ?string
  {
    return $this->createdAt;
  }

  public function setCreatedAt(?string $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getBatchNumber(): ?string
  {
    return $this->batchNumber;
  }

  public function setBatchNumber(?string $batchNumber): void
  {
    $this->batchNumber = $batchNumber;
  }

  public function getProductName(): ?string
  {
    return $this->productName;
  }

  public function setProductName(?string $productName): void
  {
    $this->productName = $productName;
  }

  public function getUserName(): ?string
  {
    return $this->userName;
  }

  public function setUserName(?string $userName): void
  {
    $this->userName = $userName;
  }
}
