<?php

/**
 * User roles in the application
 */
enum Role: string
{
  case ADMIN = 'ADMIN';
  case PREPARATEUR = 'PREPARATEUR';
  case PHARMACIEN = 'PHARMACIEN';

  /**
   * Get human-readable label for the role
   */
  public function label(): string
  {
    return match ($this) {
      self::ADMIN => 'Administrateur',
      self::PREPARATEUR => 'Préparateur',
      self::PHARMACIEN => 'Pharmacien',
    };
  }
}
