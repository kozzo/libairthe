<?php
// src/Enum/ExperienceLevel.php
namespace App\Enum;

enum ExperienceLevel: int
{
    case DEBUTANT = 0;        // 0 points
    case APPRENTI = 100;      // 100 points
    case COMPAGNON = 250;     // 250 points
    case MAITRE = 500;        // 500 points
    case GRAND_MAITRE = 1000; // 1000 points

    public static function getLevelByPoints(int $points): self
    {
        foreach (self::cases() as $level) {
            if ($points < $level->value) {
                return $level;
            }
        }

        return self::GRAND_MAITRE; // Le plus haut niveau
    }

    public function getNextLevelPoints(): int
    {
        return match ($this) {
            self::DEBUTANT => self::APPRENTI->value,
            self::APPRENTI => self::COMPAGNON->value,
            self::COMPAGNON => self::MAITRE->value,
            self::MAITRE => self::GRAND_MAITRE->value,
            self::GRAND_MAITRE => PHP_INT_MAX, // Aucun niveau suivant
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DEBUTANT => 'Débutant',
            self::APPRENTI => 'Apprenti',
            self::COMPAGNON => 'Compagnon',
            self::MAITRE => 'Maître',
            self::GRAND_MAITRE => 'Grand Maître',
        };
    }
}