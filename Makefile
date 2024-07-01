.PHONY:  deploy 

defaul: check

deploy: vendor/autoload.php

	cd public &&	rm -R asset/
	php bin/console tailwind:build --minify
	php bin/console asset-map:compile
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php


check :
	./vendor/bin/php-cs-fixer fix src
	vendor/bin/phpstan analyse src