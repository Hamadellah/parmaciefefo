<?php

/**
 * StockBatch entity - represents a batch of product in stock
 */
class StockBatch
{
  private ?int $id = null;
  private int $productId = 0;
  private string $batchNumber = '';
  private int $quantity = 0;
  private string $expiryDate = '';
  private string $status = 'ACTIVE';
  private ?string $createdAt = null;

  // Extra fields joined from queries (not stored directly)
  private ?string $productName = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function getProductId(): int
  {
    return $this->productId;
  }

  public function setProductId(int $productId): void
  {
    $this->productId = $productId;
  }

  public function getBatchNumber(): string
  {
    return $this->batchNumber;
  }

  public function setBatchNumber(string $batchNumber): void
  {
    $this->batchNumber = $batchNumber;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): void
  {
    $this->quantity = $quantity;
  }

  public function getExpiryDate(): string
  {
    return $this->expiryDate;
  }

  public function setExpiryDate(string $expiryDate): void
  {
    $this->expiryDate = $expiryDate;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function setStatus(string $status): void
  {
    $this->status = $status;
  }

  public function getCreatedAt(): ?string
  {
    return $this->createdAt;
  }

  public function setCreatedAt(?string $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getProductName(): ?string
  {
    return $this->productName;
  }

  public function setProductName(?string $productName): void
  {
    $this->productName = $productName;
  }
}
