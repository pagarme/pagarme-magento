#!/bin/bash

docker-compose up -d

magentoHasBeenDownloaded() {
  docker-compose exec magento test -e install.php

  if [ $? -eq 0 ]
  then
    return 0
  else
    return 1
  fi
}

magentoIsInstalled() {
  isInstalled=$(docker-compose exec magento php -f install.php | grep "Magento is already installed" | wc -l)

  if [ $isInstalled -eq 1 ] 
  then
    return 0
  else
    return 1
  fi
}

while ! ( magentoHasBeenDownloaded && magentoIsInstalled )
do
  echo [$(date +"%Y-%m-%d %H:%M:%S")] - "Waiting for magento to be installed..."
  sleep 15
done

docker-compose exec magento /opt/docker/bin/composer install
docker-compose exec magento php index.php
docker-compose exec magento vendor/bin/n98-magerun dev:log --on --global

for testScript in $(ls ./script/test-*.sh); do
    /bin/bash $testScript

    stepExitCode=$?

    if [[ -z $exitCode || $stepExitCode -ne 0 ]]
    then
        exitCode=$stepExitCode
    fi
done

exit $exitCode
