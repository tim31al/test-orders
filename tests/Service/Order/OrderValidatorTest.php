<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Service\Order;

use App\Service\Order\OrderValidationException;
use App\Service\Order\OrderValidator;
use PHPUnit\Framework\TestCase;

class OrderValidatorTest extends TestCase
{
    public function testSomething(): void
    {
        $validator = new OrderValidator();
        $this->assertInstanceOf(OrderValidator::class, $validator);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidate($data, $errors): void
    {
        $validator = new OrderValidator();


        try {
            $validator->validate($data);
            $this->assertTrue(0);
        } catch (OrderValidationException $e) {
            $this->assertSame($e->getErrors(), $errors);
        }

    }

    public function dataProvider(): array
    {
        $data = [
            'orderNumber' => 'bfb470d73bf615414f3d2',
            'lastname' => 'Ларионов',
            'firstname' => 'Игнатий',
            'price' => 1000,
            'address' => [
                'city' => 'Москва',
                'street' => 'Радио',
                'apartment' => 33,
            ],
        ];

        $data1 = $data;
        unset($data1['orderNumber']);

        $data2 = $data;
        unset($data2['lastname']);

        $data3 = $data;
        unset($data3['firstname']);

        $data4 = $data;
        unset($data4['price']);

        $data5 = $data;
        unset($data5['address']);

        $data6 = $data;
        $data6['address'] = 'string';

        $data7 = $data;
        $data7['lastname'] = 22;
        $data7['orderNumber'] = 22;
        $data7['firstname'] = 100;
        $data7['price'] = '100';

        $data8 = $data;
        $data8['address'] = [
            'city' => 'Moscow',
        ];

        return [
            [$data1, ['[orderNumber] : This field is missing.']],
            [$data2, ['[lastname] : This field is missing.']],
            [$data3, ['[firstname] : This field is missing.']],
            [$data4, ['[price] : This field is missing.']],
            [$data5, ['[address] : This field is missing.']],
            [$data6, ['[address] : This value should be of type array|(Traversable&ArrayAccess).']],
            [$data7, [
                '[orderNumber] : This value should be of type string.',
                '[lastname] : This value is not valid.',
                '[firstname] : This value is not valid.',
                '[price] : This value should be of type integer.',
            ]],
            [$data8, [
                '[address][city] : This value is not valid.',
                '[address][street] : This field is missing.',
            ]],
        ];
    }
}
