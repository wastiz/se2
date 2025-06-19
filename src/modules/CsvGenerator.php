<?php
class CsvGenerator {
    private $outputDir = 'output/';

    public function __construct() {
        $this->ensureOutputDirectoryExists();
    }

    private function ensureOutputDirectoryExists() {
        if (!file_exists($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    public function generate($filename, $data) {
        if (empty($data)) {
            return;
        }

        $filePath = $this->outputDir . $filename;
        $fp = fopen($filePath, 'w');

        fwrite($fp, "\xEF\xBB\xBF");

        fputcsv($fp, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);

        return $filePath;
    }
}
