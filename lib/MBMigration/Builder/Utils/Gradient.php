<?php

namespace MBMigration\Builder\Utils;

use Exception;

class Gradient {
    private string $type;
    private string $angleOrPosition;
    private array $colors = [];

    /**
     * @throws Exception
     */
    public function __construct(string $gradient) {
        $this->parseGradient($gradient);
    }

    /**
     * @throws Exception
     */
    private function parseGradient(string $gradient): void {
        $gradient = trim($gradient);

        if (strpos($gradient, 'linear-gradient(') === 0) {
            $this->type = 'linear';
        } elseif (strpos($gradient, 'radial-gradient(') === 0) {
            $this->type = 'radial';
        } elseif (strpos($gradient, 'conic-gradient(') === 0) {
            $this->type = 'conic';
        } else {
            throw new Exception("invalid gradient: $gradient");
        }

        $gradient = substr($gradient, strpos($gradient, '(') + 1, -1);

        if ($this->type === 'linear' || $this->type === 'conic') {
            $parts = explode(',', $gradient, 2);
            $this->angleOrPosition = trim($parts[0]);

            if (!preg_match('/^(to\s+\w+|\d+deg)$/', $this->angleOrPosition)) {
                throw new Exception("invalid gradient: $gradient");
            }

            $this->parseColors($parts[1]);
        } elseif ($this->type === 'radial') {
            $parts = explode(',', $gradient, 2);
            $this->angleOrPosition = trim($parts[0]);

            $this->parseColors($parts[1]);
        }
    }

    private function parseColors(string $colors): void {
        preg_match_all('/(rgb\([^)]+\)|#[0-9a-fA-F]{3,6}|[a-zA-Z]+)(\s+\d+%)?/', $colors, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $color = $match[1];
            $percentage = isset($match[2]) ? trim($match[2]) : null;

            if (strpos($color, 'rgb(') === 0) {
                $color = ColorConverter::convertColorRgbToHex($color);
            }

            $this->colors[] = [
                'color' => $color,
                'percentage' => $percentage,
            ];
        }
    }

    public function getType(): string {
        return $this->type;
    }

    public function getAngleOrPosition(): string {
        return $this->angleOrPosition;
    }

    public function getColors(): array {
        return $this->colors;
    }
}
