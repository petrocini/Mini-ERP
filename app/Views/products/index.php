<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Cadastrar Produto</h2>
        <form action="/product/store" method="POST">
            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Preço</label>
                <input type="text" name="price" id="price" class="form-control" required>
            </div>


            <div class="mb-3">
                <label>Variações e Estoque</label>
                <div id="variations">
                    <div class="row mb-2">
                        <div class="col">
                            <input type="text" name="variations[]" class="form-control text-uppercase" style="text-transform: uppercase;">
                        </div>
                        <div class="col">
                            <input type="number" name="quantities[]" placeholder="Quantidade" class="form-control">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="addVariation()">+ Variação</button>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Produto</button>
        </form>

        <hr>
        <div class="row">
            <div class="col-md-6">
                <h3>Produtos</h3>
                <ul class="list-group">
                    <?php foreach ($products as $product): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($product->name) ?> - R$<?= number_format($product->price, 2, ',', '.') ?>

                            <div class="mt-2 d-flex gap-2 align-items-center">
                                <select class="form-select variation-select" data-product-id="<?= $product->id ?>" style="max-width: 140px;">
                                    <option>Carregando...</option>
                                </select>
                                <input type="number" class="form-control qty-input" value="1" style="width: 70px;">
                                <button class="btn btn-sm btn-success buy-btn" data-id="<?= $product->id ?>">Comprar</button>
                            </div>

                            <div class="mt-2 d-flex gap-2 align-items-center">
                                <a href="/product/edit?id=<?= $product->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/product/delete" method="POST" onsubmit="return confirm('Deseja excluir este produto?')" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $product->id ?>">
                                    <button class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </div>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-md-6">
                <h3>Carrinho</h3>
                <div id="cart-summary">
                    <p>Carregando...</p>
                </div>
                <a href="/checkout" class="btn btn-primary mt-3">Finalizar Pedido</a>
            </div>
        </div>

        <script>
            // carregar variações
            document.querySelectorAll('.variation-select').forEach(select => {
                const productId = select.dataset.productId;
                fetch(`/api/variations?product_id=${productId}`)
                    .then(res => res.json())
                    .then(data => {
                        select.innerHTML = '';
                        if (data.length === 0) {
                            select.innerHTML = '<option value="">Sem variações</option>';
                        } else {
                            data.forEach(v => {
                                const opt = document.createElement('option');
                                opt.value = v;
                                opt.textContent = v;
                                select.appendChild(opt);
                            });
                        }
                    });
            });

            // adicionar produto via ajax
            document.querySelectorAll('.buy-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    const parent = button.closest('li');
                    const variation = parent.querySelector('.variation-select').value;
                    const quantity = parent.querySelector('.qty-input').value;

                    fetch('/cart/add-ajax', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                product_id: productId,
                                variation: variation,
                                quantity: quantity
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Produto adicionado ao carrinho!');
                                loadCart();
                            } else {
                                alert(data.error);
                            }
                        });
                });
            });

            function loadCart() {
                fetch('/cart/items')
                    .then(res => res.json())
                    .then(items => {
                        const container = document.getElementById('cart-summary');
                        if (items.length === 0) {
                            container.innerHTML = '<p>Carrinho vazio.</p>';
                            return;
                        }

                        let html = '<table class="table table-sm"><thead><tr><th>Produto</th><th>Variação</th><th>Quantidade</th><th>Valor</th><th></th></tr></thead><tbody>';

                        items.forEach((item, index) => {
                            html += `<tr>
          <td>${item.name}</td>
          <td>${item.variation}</td>
          <td>${item.quantity}</td>
          <td>R$ ${item.unit.toFixed(2).replace('.', ',')}</td>
          <td>
            <form onsubmit="return removeItem('${item.key}')" class="d-inline">
              <button type="submit" class="btn btn-sm btn-outline-danger">X</button>
            </form>
          </td>
        </tr>`;
                        });

                        html += '</tbody></table>';
                        html += `<button onclick="clearCart()" class="btn btn-sm btn-danger">Limpar Carrinho</button>`;
                        container.innerHTML = html;
                    });
            }

            function removeItem(key) {
                fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        key: key
                    })
                }).then(() => loadCart());

                return false;
            }

            function clearCart() {
                fetch('/cart/clear')
                    .then(() => loadCart());
            }



            document.addEventListener('DOMContentLoaded', loadCart);
        </script>


    </div>

    <script>
        function addVariation() {
            const container = document.getElementById('variations');
            container.insertAdjacentHTML('beforeend', `
        <div class="row mb-2">
          <div class="col">
                            <input type="text" name="variations[]" class="form-control text-uppercase" style="text-transform: uppercase;"></div>
          <div class="col"><input type="number" name="quantities[]" placeholder="Quantidade" class="form-control"></div>
        </div>
      `);
        }
    </script>
</body>

<script>
    const priceInput = document.getElementById('price');

    function formatCurrency(value) {
        // Remove tudo que não for número
        const clean = value.replace(/\D/g, '');
        const num = parseFloat(clean) / 100;

        // Se não for número válido, retorna 0,00
        if (isNaN(num)) return 'R$ 0,00';

        return 'R$ ' + num.toFixed(2).replace('.', ',');
    }

    function parseCurrency(value) {
        return value
            .replace(/\s/g, '')
            .replace('R$', '')
            .replace('.', '')
            .replace(',', '.');
    }

    priceInput.addEventListener('input', () => {
        priceInput.value = formatCurrency(priceInput.value);
    });

    priceInput.addEventListener('blur', () => {
        priceInput.value = formatCurrency(priceInput.value);
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.variation-select').forEach(select => {
            const productId = select.dataset.productId;

            fetch(`/api/variations?product_id=${productId}`)
                .then(res => res.json())
                .then(variations => {
                    select.innerHTML = ''; // limpa opções
                    if (variations.length === 0) {
                        select.innerHTML = '<option value="">Sem variações</option>';
                    } else {
                        variations.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v;
                            opt.textContent = v;
                            select.appendChild(opt);
                        });
                    }
                });
        });
    });
</script>


</html>