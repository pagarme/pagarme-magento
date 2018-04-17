#!/bin/bash

docker-compose exec -T magento vendor/bin/behat --stop-on-failure
