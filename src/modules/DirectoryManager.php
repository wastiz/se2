<?php
class DirectoryManager {
  private $mediaDir = 'output/media/';
  private $archiveDir = 'output/archive/';
  private $currentArchiveDir;

  public function __construct() {
    $this->initDirectories();
  }

  private function initDirectories() {
    if (!file_exists($this->mediaDir)) mkdir($this->mediaDir, 0777, true);
    if (!file_exists($this->archiveDir)) mkdir($this->archiveDir, 0777, true);

    $this->currentArchiveDir = $this->archiveDir . 'run_' . date('Y-m-d_H-i-s') . '/';
    mkdir($this->currentArchiveDir, 0777, true);
  }

  public function archiveFolder($folder, $sku) {
    rename($folder, $this->currentArchiveDir . $sku);
  }

  public function getMediaDir() {
    return $this->mediaDir;
  }

  public function removeProductFolder($folder) {
    if (file_exists($folder)) {
      $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
      );

      foreach ($files as $fileinfo) {
        $fileinfo->isDir() ? rmdir($fileinfo->getRealPath()) : unlink($fileinfo->getRealPath());
      }

      rmdir($folder);
    }
  }
}
