<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    public function jsonResponse(): Response
    {
    }
}
