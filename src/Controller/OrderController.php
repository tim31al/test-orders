<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller;

use App\Controller\Traits\LimitOffsetTrait;
use App\Repository\OrderRepository;
use App\Service\Order\OrderService;
use App\Service\Order\OrderValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders')]
class OrderController extends AbstractController
{
    use LimitOffsetTrait;

    #[Route('', name: 'app_orders', methods: ['GET'])]
    public function index(Request $request, OrderRepository $orderRepository): Response
    {
        list($limit, $offset) = $this->getLimitOffset($request);

        $orders = $orderRepository->findBy([], ['createdAt' => 'desc'], $limit, $offset);

        return $this->json($orders, Response::HTTP_OK, [], ['groups' => 'order_index']);
    }

    #[Route('/{id}', name: 'app_order', methods: ['GET'])]
    public function show(int $id, OrderRepository $orderRepository): Response
    {
        $statusCode = Response::HTTP_OK;
        $order = $orderRepository->find($id);
        if (!$order) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        return $this->json($order, $statusCode, [], ['groups' => 'order_show']);
    }

    #[Route('', name: 'app_order_create', methods: ['POST'])]
    public function create(Request $request, OrderService $orderService): Response
    {
        $statusCode = Response::HTTP_CREATED;

        try {
            $body = json_decode($request->getContent(), true);
            $data = $orderService->create($body);
        } catch (OrderValidationException $e) {
            $data = $e->getErrors();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return $this->json($data, $statusCode, [], ['groups' => 'order_show']);
    }

    #[Route('/{id}', name: 'app_order_update', methods: ['PUT'])]
    public function update(Request $request, int $id, OrderService $orderService): Response
    {
        $statusCode = Response::HTTP_OK;

        try {
            $body = json_decode($request->getContent(), true);
            $data = $orderService->update($id, $body);
        } catch (OrderValidationException $e) {
            $data = $e->getErrors();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return $this->json($data, $statusCode, [], ['groups' => 'order_show']);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['DELETE'])]
    public function delete(int $id, OrderService $orderService): Response
    {
        $statusCode = Response::HTTP_OK;

        try {
            $data = $orderService->delete($id);
        } catch (OrderValidationException $e) {
            $data = $e->getErrors();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return $this->json($data, $statusCode);
    }
}
