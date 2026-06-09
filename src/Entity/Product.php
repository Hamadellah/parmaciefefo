<?php

/**
 * Product entity - represents a pharmacy product
 */
class Product
{
  private ?int $id = null;
  private string $name = '';
  private string $description = '';
  private string $category = 'General';
  private string $unit = 'unité';
  private ?string $createdAt = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  public function getCategory(): string
  {
    return $this->category;
  }

  public function setCategory(string $category): void
  {
    $this->category = $category;
  }

  public function getUnit(): string
  {
    return $this->unit;
  }

  public function setUnit(string $unit): void
  {
    $this->unit = $unit;
  }

  public function getCreatedAt(): ?string
  {
    return $this->createdAt;
  }

  public function setCreatedAt(?string $createdAt): void
  {
    $this->createdAt = $createdAt;
  }
}
