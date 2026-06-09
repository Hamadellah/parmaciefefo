<?php

/**
 * Alert entity - expiration alert for a stock batch
 */
class Alert
{
  private ?int $id = null;
  private int $batchId = 0;
  private string $level = 'green';
  private string $message = '';
  private bool $isRead = false;
  private ?string $createdAt = null;

  // Extra joined fields
  private ?string $batchNumber = null;
  private ?string $productName = null;
  private ?string $expiryDate = null;

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

  public function getLevel(): string
  {
    return $this->level;
  }

  public function setLevel(string $level): void
  {
    $this->level = $level;
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function setMessage(string $message): void
  {
    $this->message = $message;
  }

  public function isRead(): bool
  {
    return $this->isRead;
  }

  public function setIsRead(bool $isRead): void
  {
    $this->isRead = $isRead;
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

  public function getExpiryDate(): ?string
  {
    return $this->expiryDate;
  }

  public function setExpiryDate(?string $expiryDate): void
  {
    $this->expiryDate = $expiryDate;
  }
}
