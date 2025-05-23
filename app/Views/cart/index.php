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
            <p>O carrinho está vazio.</p>
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

            <div class="alert alert-secondary">
                <p><strong>Subtotal:</strong> R$<?= number_format($subtotal, 2, ',', '.') ?></p>
                <p><strong>Frete:</strong> R$<?= number_format($frete, 2, ',', '.') ?></p>
                <p><strong>Total:</strong> R$<?= number_format($total, 2, ',', '.') ?></p>
            </div>
        <?php endif; ?>
        <a href="/" class="btn btn-secondary">Voltar para Produtos</a>
    </div>
</body>

</html>