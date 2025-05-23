<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;

class ProductController
{
    private ProductRepository $productRepo;
    private StockRepository $stockRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->stockRepo = new StockRepository();
    }

    public function index()
    {
        $products = $this->productRepo->all();
        require __DIR__ . '/../Views/products/index.php';
    }

    public function store()
    {
        $rawPrice = $_POST['price'] ?? 'R$ 0,00';
        $cleanPrice = str_replace(['R$', '.', ','], ['', '', '.'], $rawPrice);
        $price = floatval($cleanPrice);

        // Garantir que seja número válido
        if (!is_numeric($price)) {
            $price = 0.00;
        }

        $product = new Product([
            'name' => $_POST['name'],
            'price' => floatval($price),
        ]);

        $productId = $this->productRepo->create($product);

        if (!empty($_POST['variations']) && !empty($_POST['quantities'])) {
            foreach ($_POST['variations'] as $i => $variation) {
                $stock = new Stock([
                    'product_id' => $productId,
                    'variation' => strtoupper($variation),
                    'quantity' => $_POST['quantities'][$i],
                ]);
                $this->stockRepo->create($stock);
            }
        }

        header('Location: /');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /');
            exit;
        }

        $product = $this->productRepo->find((int) $id);
        $stock = $this->stockRepo->findByProduct((int) $id);

        require __DIR__ . '/../Views/products/edit.php';
    }

    public function update()
    {
        $product = new Product([
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'price' => $_POST['price']
        ]);

        $this->productRepo->update($product);

        // Atualiza variações
        $this->stockRepo->deleteByProduct($product->id);

        foreach ($_POST['variations'] as $i => $variation) {
            $stock = new Stock([
                'product_id' => $product->id,
                'variation' => $variation,
                'quantity' => $_POST['quantities'][$i],
            ]);
            $this->stockRepo->create($stock);
        }

        header('Location: /');
        exit;
    }
    public function variations()
    {
        header('Content-Type: application/json');

        $productId = $_GET['product_id'] ?? null;
        if (!$productId) {
            echo json_encode([]);
            return;
        }

        $variations = $this->stockRepo->findByProduct((int) $productId);
        $options = array_map(fn($v) => $v->variation, $variations);

        echo json_encode($options);
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /');
            exit;
        }

        $this->productRepo->delete((int) $id);
        $this->stockRepo->deleteByProduct((int) $id); // opcional, ON DELETE CASCADE já resolve

        header('Location: /');
        exit;
    }
}
