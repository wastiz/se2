<?php
require_once 'src/modules/DirectoryManager.php';
require_once 'src/modules/ProductProcessor.php';
require_once 'src/modules/CsvGenerator.php';
require_once 'src/modules/DatabaseManager.php';
require_once 'src/DTO/Product.php';

class ProductImporter {
    private $existingSkus;
    private $newProducts = [];
    private $existingProducts = [];
    private $directoryManager;
    private $productProcessor;
    private $csvGenerator;
    private $databaseManager;

    public function __construct(array $existingSkus = []) {
        $this->existingSkus = $existingSkus;
        $this->directoryManager = new DirectoryManager();
        $this->productProcessor = new ProductProcessor($this->directoryManager);
        $this->csvGenerator = new CsvGenerator();
        $this->databaseManager = new DatabaseManager();
    }

    public function processProducts() {
        $sourceDir = 'src/se2/';
        $skuFolders = glob($sourceDir . '*', GLOB_ONLYDIR);

        $this->databaseManager->beginTransaction();

        try {
            foreach ($skuFolders as $folder) {
                $sku = basename($folder);
                try {
                    $productData = $this->productProcessor->processProductFolder($folder, $sku);

                    if ($productData === null) {
                        continue;
                    }

                    $product = new Product(
                        $productData['sku'],
                        $productData['name'],
                        $productData['description'],
                        $productData['image'],
                        $productData['specifications']
                    );

                    $this->databaseManager->saveProduct($product);

                    if (in_array($sku, $this->existingSkus)) {
                        $this->existingProducts[] = $productData;
                    } else {
                        $this->newProducts[] = $productData;
                    }

                    $this->directoryManager->archiveFolder($folder, $sku);

                } catch (Exception $e) {
                    error_log("Error processing SKU {$sku}: " . $e->getMessage());
                    continue;
                }
            }

            $this->databaseManager->commit();

            $this->csvGenerator->generate('existing_products.csv', $this->existingProducts);
            $this->csvGenerator->generate('new_products.csv', $this->newProducts);

        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            error_log("Import failed: " . $e->getMessage());
            throw $e;
        }
    }
}