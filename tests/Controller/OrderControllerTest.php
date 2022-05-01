<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Controller;

use App\Tests\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    private const ORDER_KEYS = ['id', 'orderNumber', 'lastname', 'firstname', 'price', 'address', 'createdAt'];

    use HelperTrait;

    public function testIndexAllOrders(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders');

        $this->assertResponseIsSuccessful();

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame($statusCode, 200);

        $data = $this->getResponseData($client);
        $this->assertCount(3, $data);
    }

    public function testIndexLimit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders?limit=2');

        $this->assertResponseIsSuccessful();
        $data = $this->getResponseData($client);

        $this->assertCount(2, $data);
    }

    public function testIndexLimitOffset(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders?limit=2&offset=2');

        $this->assertResponseIsSuccessful();
        $data = $this->getResponseData($client);

        $this->assertCount(1, $data);
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders?limit=1');

        list($order) = $this->getResponseData($client);

        $client->request('GET', '/orders/'.$order['id']);

        $data = $this->getResponseData($client);
        $this->assertArrayHasKeys(static::ORDER_KEYS, $data);
    }

    public function testShowBadId(): void
    {
        $client = static::createClient();

        $client->request('GET', '/orders/0');

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame(404, $statusCode);

        $data = $this->getResponseData($client);
        $this->assertNull($data);
    }

    public function testCreate(): void
    {
        $data = [
            'orderNumber' => '7654321',
            'lastname' => 'Ларионов',
            'firstname' => 'Игнатий',
            'price' => 1000,
            'address' => [
                'city' => 'Москва',
                'street' => 'Радио',
                'apartment' => 33,
            ],
        ];

        $client = static::createClient();
        $content = json_encode($data);

        $client->request('POST', '/orders', [], [], [], $content);

        $this->assertResponseIsSuccessful();

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame(201, $statusCode);

        $order = $this->getResponseData($client);

        $this->assertArrayHasKeys(static::ORDER_KEYS, $order);
        $this->assertSame($data['orderNumber'], $order['orderNumber']);
        $this->assertSame($data['lastname'], $order['lastname']);
        $this->assertSame($data['firstname'], $order['firstname']);
        $this->assertSame($data['price'], $order['price']);
        $this->assertSame($data['address'], $order['address']);
    }

    public function testCreateBadRequest(): void
    {
        $data = [
            'orderNumber' => '123456789',
            'lastname' => 'Ларионов',
        ];

        $client = static::createClient();
        $content = json_encode($data);

        $client->request('POST', '/orders', [], [], [], $content);

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame(400, $statusCode);
    }

    public function testUpdate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders?limit=1');

        list($order) = $this->getResponseData($client);

        $id = $order['id'];
        $order['orderNumber'] = '777';
        $order['address'] = [
            'city' => 'Сити',
            'street' => 'Стрит',
        ];
        unset($order['createdAt']);
        unset($order['id']);
        $content = json_encode($order);

        $client->request('PUT', '/orders/'.$id, [], [], [], $content);

        $this->assertResponseIsSuccessful();

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame(200, $statusCode);

        $order = $this->getResponseData($client);
        $this->assertSame('777', $order['orderNumber']);
        $this->assertSame([
            'city' => 'Сити',
            'street' => 'Стрит',
        ], $order['address']);
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('GET', '/orders?limit=1');

        list($order) = $this->getResponseData($client);

        $id = $order['id'];
        $client->request('DELETE', '/orders/'.$id);

        $this->assertResponseIsSuccessful();

        $statusCode = $this->getResponseStatus($client);
        $this->assertSame(200, $statusCode);

        $data = $this->getResponseData($client);
        $this->assertSame(['deleted' => $id], $data);
    }
}
