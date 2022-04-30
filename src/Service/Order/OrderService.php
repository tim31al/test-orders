<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class OrderService
{
    private const ORDER_NOT_FOUND = ['id' => 'order not found'];

    private OrderRepository $orderRepository;
    private OrderValidator $orderValidator;
    private EntityManagerInterface $entityManager;

    /**
     * @param \App\Service\Order\OrderValidator $orderValidator
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderValidator $orderValidator,
        EntityManagerInterface $entityManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderValidator = $orderValidator;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \App\Service\Order\OrderValidationException
     */
    public function update(int $id, array $data): ?Order
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new OrderValidationException(static::ORDER_NOT_FOUND);
        }

        $this->orderValidator->validate($data);

        $this->setOrderData($order, $data);

        try {
            $this->entityManager->flush();

            return $order;
        } catch (UniqueConstraintViolationException $e) {
            $errors = [
                '[orderNumber]' => 'must be a unique',
            ];
            throw new OrderValidationException($errors);
        }
    }

    /**
     * @throws \App\Service\Order\OrderValidationException
     */
    public function create(array $data): ?Order
    {
        $this->orderValidator->validate($data);

        $order = new Order();
        $this->setOrderData($order, $data);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    /**
     * @throws \App\Service\Order\OrderValidationException
     */
    #[ArrayShape(['deleted' => "int"])]
    public function delete(int $id): array
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new OrderValidationException(static::ORDER_NOT_FOUND);
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return ['deleted' => $id];
    }

    private function setOrderData(Order &$order, array $data): void
    {
        $order
            ->setLastname($data['lastname'])
            ->setFirstname($data['firstname'])
            ->setPrice($data['price'])
            ->setAddress($data['address'])
            ->setOrderNumber($data['orderNumber'])
        ;
    }
}
