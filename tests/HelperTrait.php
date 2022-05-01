<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait HelperTrait
{
    public function getResponseData(KernelBrowser $client): ?array
    {
        $data = $client->getResponse()->getContent();

        return json_decode($data, true);
    }

    public function getResponseStatus(KernelBrowser $client): int
    {
        return $client->getResponse()->getStatusCode();
    }

    public function assertArrayHasKeys(array $keys, array $data): void
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }
}
