<?php
class ImageProcessor {
  private $directoryManager;

  public function __construct(DirectoryManager $directoryManager) {
    $this->directoryManager = $directoryManager;
  }

  public function process($folder, $sku) {
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imagePath = null;

    foreach ($imageExtensions as $ext) {
      $files = glob($folder . '/*.' . $ext);
      if (!empty($files)) {
        $sourceImage = $files[0];
        $targetImage = $this->directoryManager->getMediaDir() . $sku . '.png';

        if (copy($sourceImage, $targetImage)) {
          $imagePath = $targetImage;
          break;
        }
      }
    }

    if (!$imagePath) {
      throw new Exception("Image not found for SKU {$sku}");
    }

    return $imagePath;
  }
}
