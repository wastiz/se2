<?php
require_once 'src/modules/ProductImporter.php';

$existingSkus = ['BCPMC224S', 'LC1D386MD'];
$importer = new ProductImporter($existingSkus);
$importer->processProducts();
echo "Completed";
