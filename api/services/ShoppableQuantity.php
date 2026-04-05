<?php
require_once __DIR__ . '/UnitConverter.php';
require_once __DIR__ . '/../models/Database.php';

class ShoppableQuantity {

    private ?array $packageCache = null;

    /**
     * Convert a recipe amount into a shoppable package quantity.
     *
     * Returns null if:
     * - ingredient not found in ingredient_data
     * - no package info available
     * - units are incompatible (volume vs weight without density)
     *
     * Returns array with: packages_needed, package_size, package_unit,
     *   package_description, package_label, fraction_of_package
     */
    public function convert(?string $amount, ?string $unit, string $ingredientName): ?array {
        $parsedAmount = UnitConverter::parseAmount($amount);
        if ($parsedAmount === null || $parsedAmount <= 0) {
            return null;
        }

        $package = $this->findPackageInfo($ingredientName);
        if (!$package) {
            return null;
        }

        $pkgSize = (float) $package['package_size'];
        $pkgUnit = $package['package_unit'];
        $pkgDesc = $package['package_description'];

        // Handle count-based packages (eggs, etc.)
        if ($pkgUnit === 'count') {
            $fraction = $parsedAmount / $pkgSize;
            return $this->buildResult($fraction, $pkgSize, $pkgUnit, $pkgDesc);
        }

        // Check if recipe unit and package unit are convertible
        $recipeUnit = $unit;
        if ($recipeUnit === null) {
            return null;
        }

        if (!UnitConverter::canConvert($recipeUnit, $pkgUnit)) {
            return null;
        }

        // Convert recipe amount to package unit
        $amountInPkgUnit = UnitConverter::convert($parsedAmount, $recipeUnit, $pkgUnit);
        if ($amountInPkgUnit === null) {
            return null;
        }

        $fraction = $amountInPkgUnit / $pkgSize;
        return $this->buildResult($fraction, $pkgSize, $pkgUnit, $pkgDesc);
    }

    private function buildResult(float $fraction, float $pkgSize, string $pkgUnit, string $pkgDesc): array {
        $packagesNeeded = (int) ceil($fraction);
        if ($packagesNeeded < 1) $packagesNeeded = 1;

        $label = $pkgUnit === 'count'
            ? "{$pkgSize} count"
            : UnitConverter::formatAmount($pkgSize) . " {$pkgUnit}";

        return [
            'packages_needed' => $packagesNeeded,
            'package_size' => $pkgSize,
            'package_unit' => $pkgUnit,
            'package_description' => $pkgDesc,
            'package_label' => $label,
            'fraction_of_package' => round($fraction, 3),
        ];
    }

    private function findPackageInfo(string $ingredientName): ?array {
        $this->loadCache();
        $key = strtolower(trim($ingredientName));

        // Exact match first
        if (isset($this->packageCache[$key])) {
            return $this->packageCache[$key];
        }

        // Substring match: check if ingredient name contains a known key
        foreach ($this->packageCache as $name => $info) {
            if (str_contains($key, $name) || str_contains($name, $key)) {
                return $info;
            }
        }

        return null;
    }

    private function loadCache(): void {
        if ($this->packageCache !== null) return;

        $db = Database::getInstance();
        $stmt = $db->query('SELECT name, package_size, package_unit, package_description FROM ingredient_data WHERE package_size IS NOT NULL');
        $rows = $stmt->fetchAll();

        $this->packageCache = [];
        foreach ($rows as $row) {
            $this->packageCache[strtolower($row['name'])] = $row;
        }
    }
}
