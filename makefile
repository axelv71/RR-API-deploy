.PHONY: format
format:
	./vendor/bin/php-cs-fixer fix

.PHONY: db
db:
	- git pull
	rm -rf ./migrations/*.php

	docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:database:drop --if-exists --force"
	docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:database:create --if-not-exists"
	docker exec -ti www_cube /bin/bash -c "php bin/console make:migration"
	docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:migrations:migrate --no-interaction"
	docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:fixtures:load --no-interaction"