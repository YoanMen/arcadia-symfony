.PHONY:  deploy 

deploy: vendor/autoload.php

	cd public &&	rm -R asset/
	php bin/console tailwind:build --minify
	php bin/console asset-map:compile
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php


