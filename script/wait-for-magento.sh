#!/usr/bin/env bash

magentoIsInstalled() {
  isInstalled=$(docker-compose exec magento php -f install.php | grep "Magento is already installed" | wc -l)

  if [ $isInstalled -eq 1 ] 
  then
    return 0
  else
    return 1
  fi
}

magentoHasBeenDownloaded() {
  docker-compose exec magento test -e install.php

  if [ $? -eq 0 ]
  then
    return 0
  else
    return 1
  fi
}


while ! ( magentoHasBeenDownloaded && magentoIsInstalled )
do
  echo [$(date +"%Y-%m-%d %H:%M:%S")] - "Waiting for magento to be installed..."
  sleep 5
done