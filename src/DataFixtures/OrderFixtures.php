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
use Faker\Factory;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        foreach (range(1, 10) as $_) {
            $address = [
                'city' => $faker->city,
                'street' => $faker->streetName,
            ];

            if (rand(1, 3)) {
                $address['apartment'] = rand(1, 100);
            }

            $orderNumber = bin2hex(random_bytes(10));

            $order = new Order();
            $order
                ->setOrderNumber($orderNumber)
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setAddress($address)
                ->setPrice(rand(100, 10000));

            $manager->persist($order);
        }

        $manager->flush();
    }
}
