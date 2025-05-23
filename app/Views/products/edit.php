<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Editar Produto</h2>
        <form action="/product/update" method="POST">
            <input type="hidden" name="id" value="<?= $product->id ?>">

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product->name) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Preço</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product->price ?>" required>
            </div>

            <div class="mb-3">
                <label>Variações e Estoque</label>
                <div id="variations">
                    <?php foreach ($stock as $item): ?>
                        <div class="row mb-2">
                            <div class="col">
                                <input type="text" name="variations[]" class="form-control text-uppercase" style="text-transform: uppercase;">
                            </div>
                            <div class="col">
                                <input type="number" name="quantities[]" class="form-control" value="<?= $item->quantity ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="addVariation()">+ Variação</button>
            </div>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="/" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script>
        function addVariation() {
            const container = document.getElementById('variations');
            container.insertAdjacentHTML('beforeend', `
        <div class="row mb-2">
          <div class="col"><input type="text" name="variations[]" class="form-control"></div>
          <div class="col"><input type="number" name="quantities[]" class="form-control"></div>
        </div>
      `);
        }
    </script>
</body>

</html>