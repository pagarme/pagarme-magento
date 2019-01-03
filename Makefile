default: up-containers composer-install tailf-magento-logs toggle-mage-logs enable-mage-errors

wait-for-magento:
	@./script/wait-for-magento.sh

up-containers:
	@docker-compose up -d

tailf-magento-logs:
	docker-compose logs -f magento

down:
	@docker-compose down

check-forgotten-keys:
	@./forgotten_keys.sh

test-unit: wait-for-magento
	@docker-compose exec magento vendor/bin/phpunit

test-e2e: wait-for-magento
	@docker-compose exec magento vendor/bin/behat --stop-on-failure

test-e2e-suite: wait-for-magento
	@docker-compose exec magento vendor/bin/behat -s $(suite) --stop-on-failure

composer-install:
	@docker-compose run composer install

toggle-mage-logs:
	@docker-compose exec magento vendor/bin/n98-magerun dev:log

enable-mage-errors:
	@docker-compose exec magento mv errors/local.xml.sample errors/local.xml

get-api-key:
	@docker-compose exec magento vendor/bin/n98-magerun config:get payment/pagarme_configurations/general_api_key

set-api-key:
	@docker-compose exec magento vendor/bin/n98-magerun config:set $(api_key)

show-system-logs:
	docker-compose exec magento cat var/log/system.log

show-exception-logs:
	docker-compose exec magento cat var/log/exception.log

tailf-system-logs:
	docker-compose exec magento tail -f var/log/system.log

tailf-exception-logs:
	docker-compose exec magento tail -f var/log/exception.log

phpcs:
	@docker-compose exec magento vendor/bin/phpcs --standard=phpcsruleset.xml $(target)
