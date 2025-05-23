<?php

namespace App\Controllers;

use App\Models\Order;
use App\Repositories\CouponRepository;
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
        $quantity = (int) ($_POST['quantity'] ?? 1);

        // Verifica estoque disponível
        $stock = $this->stockRepo->findByProduct((int) $productId);
        $selectedStock = array_filter($stock, fn($s) => $s->variation === $variation);
        $available = $selectedStock ? array_values($selectedStock)[0]->quantity : 0;

        if ($quantity > $available) {
            echo "<div style='padding:1rem;font-family:sans-serif;'>
        <p><strong>Estoque insuficiente para $variation.</strong> Disponível: $available</p>
        <a href='/'>Voltar</a>
    </div>";
            exit;
        }

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
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? [];
        $couponCode = $_SESSION['coupon'] ?? null;

        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $frete = ($subtotal >= 52 && $subtotal <= 166.59) ? 15 : ($subtotal > 200 ? 0 : 20);
        $desconto = 0;

        if ($couponCode) {
            $couponRepo = new CouponRepository();
            $coupon = $couponRepo->findByCode($couponCode);

            if ($coupon && strtotime($coupon->valid_until) >= strtotime(date('Y-m-d')) && $subtotal >= $coupon->min_value) {
                $desconto = $subtotal * ($coupon->discount_percent / 100);
            } else {
                $couponCode = null; // invalida cupom
            }
        }

        $total = $subtotal + $frete - $desconto;

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

        $stockRepo = new StockRepository();

        foreach ($cart as $item) {
            $stock = $stockRepo->findByProduct((int) $item['product_id']);
            foreach ($stock as $entry) {
                if ($entry->variation === $item['variation']) {
                    $entry->quantity -= $item['quantity'];
                    $stockRepo->update($entry);
                }
            }
        }

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

    public function applyCoupon()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['coupon'] = $_POST['coupon'];
        header('Location: /cart');
        exit;
    }

    public function clear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['cart']);
        unset($_SESSION['coupon']); // também limpa cupom, se tiver
        header('Location: /cart');
        exit;
    }
}
