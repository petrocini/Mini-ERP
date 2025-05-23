<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Finalizar Pedido</h2>

        <form action="/checkout/save" method="POST">
            <div class="row mb-3">
                <div class="col">
                    <label>Nome</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label>CEP</label>
                    <input type="text" name="cep" id="cep" class="form-control" required>
                </div>
                <div class="col-9">
                    <label>Endereço</label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Finalizar Pedido</button>
            <a href="/cart" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script>
        document.getElementById('cep').addEventListener('blur', async function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                const res = await fetch(`/cep?cep=${cep}`);
                const data = await res.json();
                if (!data.erro) {
                    document.getElementById('address').value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                }
            }
        });
    </script>
</body>

</html>