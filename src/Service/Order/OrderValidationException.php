<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Order;

class OrderValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct();
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
