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
        <h3>Produtos Cadastrados</h3>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <?= htmlspecialchars($product->name) ?> - R$<?= number_format($product->price, 2, ',', '.') ?>
                    <a href="/product/edit?id=<?= $product->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="/product/delete" method="POST" onsubmit="return confirm('Deseja excluir este produto?')" class="d-inline">
                        <input type="hidden" name="id" value="<?= $product->id ?>">
                        <button class="btn btn-sm btn-outline-danger">Excluir</button>
                    </form>

                    <form action="/cart/add" method="POST" class="mt-2 d-flex align-items-center gap-2">
                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                        <select name="variation" class="form-select variation-select" data-product-id="<?= $product->id ?>" style="max-width: 160px;">
                            <option>Carregando...</option>
                        </select>
                        <input type="number" name="quantity" value="1" class="form-control" style="max-width: 80px;">
                        <button class="btn btn-sm btn-success">Comprar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>

    <script>
        function addVariation() {
            const container = document.getElementById('variations');
            container.insertAdjacentHTML('beforeend', `
        <div class="row mb-2">
          <div class="col"><input type="text" name="variations[]" placeholder="Ex: Tamanho P" class="form-control"></div>
          <div class="col"><input type="number" name="quantities[]" placeholder="Qtd" class="form-control"></div>
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