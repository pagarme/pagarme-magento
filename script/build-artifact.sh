#!/bin/bash

rm -rf ./vendor ./build
echo "Installing project (no-dev) dependencies..."
docker-compose run composer install --no-dev

echo "Creating zip file from source code and its dependencies..."
docker-compose run composer archive --file=pagarme-magento --format=zip --dir=./build

exit 0