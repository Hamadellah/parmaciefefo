<?php

/**
 * Stock batch status values
 */
enum BatchStatus: string
{
  case ACTIVE = 'ACTIVE';
  case EXPIRED = 'EXPIRED';
  case DEPLETED = 'DEPLETED';

  /**
   * Get human-readable label for the status
   */
  public function label(): string
  {
    return match ($this) {
      self::ACTIVE => 'Actif',
      self::EXPIRED => 'Expiré',
      self::DEPLETED => 'Épuisé',
    };
  }
}
