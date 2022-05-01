<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $order1 = new Order();
        $order1
            ->setOrderNumber('12345')
            ->setLastname('Иванов')
            ->setFirstname('Иван')
            ->setAddress([
                'city' => 'Москва',
                'street' => 'Радио',
                'apartment' => 5,
            ])
            ->setPrice(100);

        $manager->persist($order1);

        $order2 = new Order();
        $order2
            ->setOrderNumber('12346')
            ->setLastname('Иванова')
            ->setFirstname('Мария')
            ->setAddress([
                'city' => 'Казань',
                'street' => 'Свободы',
            ])
            ->setPrice(1000);

        $manager->persist($order2);

        $order3 = new Order();
        $order3
            ->setOrderNumber('12347')
            ->setLastname('Петров')
            ->setFirstname('Павел')
            ->setAddress([
                'city' => 'Петропавловкс',
                'street' => 'Зеленая',
                'apartment' => 5,
            ])
            ->setPrice(500);

        $manager->persist($order3);

        $manager->flush();
    }
}
