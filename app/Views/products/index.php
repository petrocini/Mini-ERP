<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style> body { padding: 30px; } </style>
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
        <input type="number" step="0.01" name="price" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Variações e Estoque</label>
        <div id="variations">
          <div class="row mb-2">
            <div class="col">
              <input type="text" name="variations[]" placeholder="Ex: Tamanho M" class="form-control">
            </div>
            <div class="col">
              <input type="number" name="quantities[]" placeholder="Qtd" class="form-control">
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
        <li><?= htmlspecialchars($product->name) ?> - R$<?= number_format($product->price, 2, ',', '.') ?></li>
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
</html>
