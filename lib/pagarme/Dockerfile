FROM php:5.6-cli
COPY . /usr/src/pagarme_php
WORKDIR /usr/src/pagarme_php
CMD [ "composer", "install" ]
CMD [ "php", "./tests/Pagarme.php" ]
