<?php
class DescriptionGenerator {
  public function generate(array $jsonData) {
    $description = $jsonData['description'] ?? '';

    $specs = $jsonData['specifications'] ?? [];
    foreach ($specs as $table) {
      foreach ($table['rows'] as $row) {
        $description .= "\n" . $row['characteristicName'] . ': ' .
          implode(', ', array_column($row['characteristicValues'], 'labelText'));
      }
    }

    return $description;
  }
}
