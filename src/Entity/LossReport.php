<?php

/**
 * LossReport entity - report of stock losses
 */
class LossReport
{
  private ?int $id = null;
  private int $batchId = 0;
  private int $quantity = 0;
  private string $reason = '';
  private int $reportedBy = 0;
  private ?string $createdAt = null;

  // Extra joined fields
  private ?string $batchNumber = null;
  private ?string $productName = null;
  private ?string $reporterName = null;

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

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): void
  {
    $this->quantity = $quantity;
  }

  public function getReason(): string
  {
    return $this->reason;
  }

  public function setReason(string $reason): void
  {
    $this->reason = $reason;
  }

  public function getReportedBy(): int
  {
    return $this->reportedBy;
  }

  public function setReportedBy(int $reportedBy): void
  {
    $this->reportedBy = $reportedBy;
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

  public function getReporterName(): ?string
  {
    return $this->reporterName;
  }

  public function setReporterName(?string $reporterName): void
  {
    $this->reporterName = $reporterName;
  }
}
