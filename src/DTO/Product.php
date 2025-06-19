<?php
class Product {
    private $sku;
    private $name;
    private $description;
    private $imagePath;
    private $specifications;

    public function __construct(
        string $sku,
        string $name,
        string $description,
        string $imagePath,
        string $specifications
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->specifications = $specifications;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getImagePath(): string {
        return $this->imagePath;
    }

    public function getSpecifications(): string {
        return $this->specifications;
    }
}