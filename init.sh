#!/bin/bash

docker-compose up -d

sleep 5

docker-compose exec -u app app php bin/console doctrine:migrations:migrate
docker-compose exec -u app app php bin/console doctrine:fixrures:load -q
