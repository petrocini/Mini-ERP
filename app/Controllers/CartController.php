<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;

class CartController
{
    private ProductRepository $productRepo;
    private StockRepository $stockRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->stockRepo = new StockRepository();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function add()
    {
        $productId = $_POST['product_id'];
        $variation = $_POST['variation'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;

        $cart = $_SESSION['cart'] ?? [];

        $key = "$productId:$variation";
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $product = $this->productRepo->find($productId);
            $cart[$key] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'variation' => $variation,
                'quantity' => $quantity
            ];
        }

        $_SESSION['cart'] = $cart;

        header('Location: /cart');
        exit;
    }

    public function view()
    {
        $cart = $_SESSION['cart'] ?? [];

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Regra de frete
        $frete = 20.00;
        if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15.00;
        if ($subtotal > 200) $frete = 0;

        $total = $subtotal + $frete;

        require __DIR__ . '/../Views/cart/index.php';
    }
}
