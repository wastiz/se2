<?php
class SpecificationFormatter {
  public function format(array $specs) {
    $formatted = [];
    foreach ($specs as $table) {
      foreach ($table['rows'] as $row) {
        $key = $row['characteristicName'];
        $values = [];

        foreach ($row['characteristicValues'] as $value) {
          $fullText = trim(implode(' ', [
            $value['beforeText'] ?? '',
            $value['labelText'] ?? '',
            $value['afterText'] ?? ''
          ]));

          if ($value['needUpperCase'] ?? false) {
            $fullText = strtoupper($fullText);
          }

          if ($value['externalUrl'] ?? null) {
            $fullText = sprintf(
              '<a href="%s">%s</a>',
              $value['externalUrl'],
              $fullText
            );
          }

          $values[] = $fullText;
        }

        $formatted[$key] = implode(', ', $values);
      }
    }
    return json_encode($formatted, JSON_UNESCAPED_UNICODE);
  }
}
