#!/bin/bash

docker-compose exec magento bash -c 'export INSTALL_INOVARTI=true && source /opt/docker/scripts/modules && installOSC'

docker-compose exec -T magento vendor/bin/behat --tags=OSC
