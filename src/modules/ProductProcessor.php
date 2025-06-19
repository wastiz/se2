<?php
require_once 'src/modules/DirectoryManager.php';
require_once 'src/modules/DescriptionGenerator.php';
require_once 'src/modules/ImageProcessor.php';
require_once 'src/modules/SpecificationFormatter.php';
class ProductProcessor {
  private $directoryManager;
  private $descriptionGenerator;
  private $imageProcessor;
  private $specificationFormatter;

  public function __construct(DirectoryManager $directoryManager) {
    $this->directoryManager = $directoryManager;
    $this->descriptionGenerator = new DescriptionGenerator();
    $this->imageProcessor = new ImageProcessor($directoryManager);
    $this->specificationFormatter = new SpecificationFormatter();
  }

  public function processProductFolder($folder, $sku) {
    $jsonFile = $folder . '/data.json';
    if (!file_exists($jsonFile)) {
      throw new Exception("JSON file not found for SKU {$sku}");
    }

    $jsonData = json_decode(file_get_contents($jsonFile), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception("Invalid JSON for SKU {$sku}");
    }

    return [
      'sku' => $sku,
      'name' => $jsonData['name'] ?? '',
      'description' => $this->descriptionGenerator->generate($jsonData),
      'image' => $this->imageProcessor->process($folder, $sku),
      'specifications' => $this->specificationFormatter->format($jsonData['specifications'] ?? [])
    ];
  }
}
