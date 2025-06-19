<?php
class CsvGenerator {
  public function generate($filename, $data) {
    if (empty($data)) return;

    $fp = fopen($filename, 'w');
    fputcsv($fp, array_keys($data[0]));

    foreach ($data as $row) {
      fputcsv($fp, $row);
    }

    fclose($fp);
  }
}
