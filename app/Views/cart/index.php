<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Carrinho</h2>
        <?php if (empty($cart)): ?>
            <p>O carrinho está vazio, volte para adicionar produtos.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Variação</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['variation']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>R$<?= number_format($item['price'], 2, ',', '.') ?></td>
                            <td>R$<?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form action="/cart/coupon" method="POST" class="mb-3">
                <div class="input-group">
                    <input type="text" name="coupon" placeholder="Cupom de desconto" class="form-control" value="<?= $couponCode ?? '' ?>">
                    <button class="btn btn-outline-primary">Aplicar</button>
                </div>
            </form>

            <div class="alert alert-secondary">
                <p><strong>Subtotal:</strong> R$<?= number_format($subtotal, 2, ',', '.') ?></p>
                <p><strong>Frete:</strong> R$<?= number_format($frete, 2, ',', '.') ?></p>
                <p><strong>Desconto:</strong> -R$<?= number_format($desconto, 2, ',', '.') ?></p>
                <p><strong>Total:</strong> R$<?= number_format($total, 2, ',', '.') ?></p>
            </div>

        <?php endif; ?>

        <?php if (!empty($cart)): ?>
            <a href="/checkout" class="btn btn-primary">Finalizar Pedido</a>
        <?php endif; ?>
        <a href="/" class="btn btn-secondary">Voltar para Produtos</a>
        <?php if (!empty($cart)): ?>
            <a href="/cart/clear" class="btn btn-danger">Limpar Carrinho</a>
        <?php endif; ?>

    </div>
</body>

</html>