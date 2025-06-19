<?php
require_once 'src/DTO/Product.php';

class DatabaseManager {
    private $pdo;
    private $tableName = 'products';

    public function __construct() {
        $config = include 'src/env.php';
        
        if (!isset($config['DB_HOST'], $config['DB_NAME'], $config['DB_USER'], $config['DB_PASS'])) {
            throw new RuntimeException("Invalid database configuration in env.php");
        }

        $host = $config['DB_HOST'];
        $port = $config['DB_PORT'] ?? 3306;
        $dbname = $config['DB_NAME'];
        $username = $config['DB_USER'];
        $password = $config['DB_PASS'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function saveProduct(Product $product): bool {
        $sql = "INSERT INTO {$this->tableName} 
                (sku, name, description, image_path, specifications, created_at, updated_at)
                VALUES (:sku, :name, :description, :image_path, :specifications, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                description = VALUES(description),
                image_path = VALUES(image_path),
                specifications = VALUES(specifications),
                updated_at = NOW()";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':sku' => $product->getSku(),
            ':name' => $product->getName(),
            ':description' => $product->getDescription(),
            ':image_path' => $product->getImagePath(),
            ':specifications' => $product->getSpecifications()
        ]);
    }

    public function productExists(string $sku): bool {
        $stmt = $this->pdo->prepare("SELECT 1 FROM {$this->tableName} WHERE sku = ? LIMIT 1");
        $stmt->execute([$sku]);
        return (bool)$stmt->fetchColumn();
    }

    public function beginTransaction(): void {
        $this->pdo->beginTransaction();
    }

    public function commit(): void {
        $this->pdo->commit();
    }

    public function rollBack(): void {
        $this->pdo->rollBack();
    }
}