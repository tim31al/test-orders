#!/bin/bash

docker-compose up -d

sleep 5

docker-compose exec -u app app composer update
docker-compose exec -u app app php bin/console doctrine:migrations:migrate -q
docker-compose exec -u app app php bin/console doctrine:fixtures:load -q
