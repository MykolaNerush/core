### Install project

0. docker-compose up
1. php bin/console doctrine:database:create
2. php bin/console doctrine:fixtures:load

### Clear DB

1. php bin/console doctrine:database:drop --force
2. php bin/console doctrine:database:create
3. php bin/console doctrine:migrations:migrate