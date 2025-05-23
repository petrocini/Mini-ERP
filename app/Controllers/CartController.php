<?php

namespace App\Controllers;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;
use App\Services\MailService;

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
    public function checkout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cart = $_SESSION['cart'] ?? [];
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $frete = ($subtotal >= 52 && $subtotal <= 166.59) ? 15 : ($subtotal > 200 ? 0 : 20);
        $total = $subtotal + $frete;

        require __DIR__ . '/../Views/cart/checkout.php';
    }

    public function cepLookup()
    {
        $cep = $_GET['cep'];
        $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
        header('Content-Type: application/json');
        echo $response;
    }

    public function saveOrder()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? [];

        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $frete = ($subtotal >= 52 && $subtotal <= 166.59) ? 15 : ($subtotal > 200 ? 0 : 20);
        $total = $subtotal + $frete;

        $order = new Order([
            'subtotal' => $subtotal,
            'shipping' => $frete,
            'total' => $total,
            'status' => 'pendente',
            'customer_name' => $_POST['name'],
            'customer_email' => $_POST['email'],
            'customer_address' => $_POST['address'],
            'cep' => $_POST['cep']
        ]);

        $orderRepo = new OrderRepository();
        $orderId = $orderRepo->create($order, $cart);

        unset($_SESSION['cart']);

        $html = "<h2>Seu pedido foi confirmado!</h2>
<p>Número do pedido: #{$orderId}</p>
<p>Total: R$" . number_format($total, 2, ',', '.') . "</p>";

        MailService::send($_POST['email'], "Confirmação de pedido #$orderId", $html);
        echo "<div style='padding:2rem;font-family:sans-serif;'>
        <h2>Pedido #$orderId finalizado com sucesso!</h2>
        <a href='/'>Voltar ao início</a>
    </div>";
    }
}
