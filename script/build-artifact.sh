#!/bin/bash

if [ "$1" == "" ]; then
    echo "There's no Git tag defined to build the artifact"
    exit 1
fi

RELEASE_TAG=$1
ARTIFACT_FILE_NAME="pagarme-magento-$RELEASE_TAG"

rm -rf ./vendor ./build
echo "Installing project (no-dev) dependencies..."
docker-compose run composer install --no-dev

echo "Creating zip file from source code and its dependencies..."
docker-compose run composer archive --file=$ARTIFACT_FILE_NAME --format=zip --dir=./build

exit 0