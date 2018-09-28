#!/bin/bash

docker-compose exec -T magento vendor/bin/behat -s configure --stop-on-failure
docker-compose exec -T magento vendor/bin/behat -s $TEST_SUITE --stop-on-failure
