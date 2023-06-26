git pull

CWD=$(pwd)

rm "$CWD"/migrations/*.php

docker stop www_cube
docker stop postgresql_cube
docker stop mailer-1
docker rm www_cube
docker rm postgresql_cube
docker rm mailer-1

docker-compose up -d

docker exec -ti www_cube /bin/bash -c "npm install"
docker exec -ti www_cube /bin/bash -c "composer update || true && composer update"
docker exec -ti www_cube /bin/bash -c "composer install"
docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:database:drop --if-exists --force"
docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:database:create --if-not-exists"
docker exec -ti www_cube /bin/bash -c "php bin/console make:migration"
docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:migrations:migrate --no-interaction"
docker exec -ti www_cube /bin/bash -c "php bin/console doctrine:fixtures:load --no-interaction"
docker exec -ti www_cube /bin/bash -c "symfony console lexik:jwt:generate-keypair --skip-if-exists"







