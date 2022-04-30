<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Order;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderValidator
{
    private ValidatorInterface $validator;
    private Collection $constraint;

    public function __construct()
    {
        $this->validator = Validation::createValidator();

        $nameRules = [
            new Assert\Regex('/^[А-я]+$/u'),
            new Assert\Length(['max' => 100]),
        ];

        $cityStreetRules = [
            new Assert\Regex('/^[А-я]+$/u'),
            new Assert\Length(['min' => 3]),
        ];

        $this->constraint = new Assert\Collection([
            'orderNumber' => [
              new Assert\Regex('/^[A-z-_\d]+/'),
              new Assert\Length(['max' => 255]),
            ],
            'lastname' => $nameRules,
            'firstname' => $nameRules,
            'address' => new Assert\Collection([
                'city' => $cityStreetRules,
                'street' => $cityStreetRules,
                'apartment' => new Assert\Optional([
                    new Assert\Type(['type' => 'integer']),
                    new Assert\Positive(),
                ]),
            ]),
            'price' => [
                new Assert\Type(['type' => 'integer']),
                new Assert\Positive(),
            ],
        ]);
    }

    /**
     * @throws \App\Service\Order\OrderValidationException
     */
    public function validate(array $row): void
    {
        $violations = $this->validator->validate($row, $this->constraint);

        $errors = [];
        if (0 !== \count($violations)) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath().' : '.$violation->getMessage();
            }

            throw new OrderValidationException($errors);
        }
    }
}
