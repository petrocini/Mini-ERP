<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;

class ProductController {
    private ProductRepository $productRepo;
    private StockRepository $stockRepo;

    public function __construct() {
        $this->productRepo = new ProductRepository();
        $this->stockRepo = new StockRepository();
    }

    public function index() {
        $products = $this->productRepo->all();
        require __DIR__ . '/../Views/products/index.php';
    }

    public function store() {
        $product = new Product([
            'name' => $_POST['name'],
            'price' => $_POST['price'],
        ]);

        $productId = $this->productRepo->create($product);

        if (!empty($_POST['variations']) && !empty($_POST['quantities'])) {
            foreach ($_POST['variations'] as $i => $variation) {
                $stock = new Stock([
                    'product_id' => $productId,
                    'variation' => $variation,
                    'quantity' => $_POST['quantities'][$i],
                ]);
                $this->stockRepo->create($stock);
            }
        }

        header('Location: /');
        exit;
    }
}
