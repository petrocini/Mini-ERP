<?php

namespace App\Controllers;

use App\Repositories\OrderRepository;

class OrderController
{
    public function webhook()
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        $id = $payload['id'] ?? null;
        $status = strtolower($payload['status'] ?? '');

        if (!$id || !$status) {
            http_response_code(400);
            echo json_encode(['error' => 'Parâmetros inválidos']);
            return;
        }

        $repo = new OrderRepository();

        if ($status === 'cancelado') {
            $repo->delete($id);
            echo json_encode(['message' => "Pedido #$id cancelado e removido."]);
        } else {
            $repo->updateStatus($id, $status);
            echo json_encode(['message' => "Status do pedido #$id atualizado para '$status'."]);
        }
    }
}
