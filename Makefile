export CONTAINER_PHP=$(shell docker ps -aqf "name=php8_3")
export WORK_DIR="/var/www/php/core.lc"
export MYSQL_DATABASE_TEST="core_test"
DOCKER_EXEC=docker exec -it $(CONTAINER_PHP) bash -c
MYSQL_ROOT_USER := $(shell docker exec -it mysql8_3 bash -c 'echo $$MYSQL_ROOT_USER')
MYSQL_ROOT_PASSWORD := $(shell docker exec -it mysql8_3 bash -c 'echo $$MYSQL_ROOT_PASSWORD')
MYSQL_DATABASE := $(shell docker exec -it mysql8_3 bash -c 'echo $$MYSQL_DATABASE')

# Styling for output
COLOR_RESET=\033[0m
COLOR_HEADER=\033[36m
COLOR_DESCRIPTION=\033[33m

help: ## Displays the list of available commands
	@printf "$(COLOR_HEADER)Available commands:$(COLOR_RESET)\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  $(COLOR_HEADER)%-20s$(COLOR_RESET) $(COLOR_DESCRIPTION)%s$(COLOR_RESET)\n", $$1, $$2}'

drop_db: ## Drops the database if it exists
	@echo "Dropping the database if it exists..."
	docker exec -it mysql8_3 mysql -u $(MYSQL_ROOT_USER) -p$(MYSQL_ROOT_PASSWORD) -e "DROP DATABASE IF EXISTS $(MYSQL_DATABASE);"

create_test_db: ## Creates the test database if it not exists
	@echo "Creating the test database: core_test"
	@docker exec -it mysql8_3 mysql -u $(MYSQL_ROOT_USER) -p$(MYSQL_ROOT_PASSWORD) -e "CREATE DATABASE IF NOT EXISTS $(MYSQL_DATABASE_TEST);"

drop_db_test: ## Drops the database if it exists
	@echo "Dropping the database if it exists..."
	docker exec -it mysql8_3 mysql -u $(MYSQL_ROOT_USER) -p$(MYSQL_ROOT_PASSWORD) -e "DROP DATABASE IF EXISTS $(MYSQL_DATABASE_TEST);"

install: drop_db drop_db_test create_test_db## Installs the project (rerun with drop_db for reinstallation)
	@echo "Installing dependencies via Composer..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && composer install'
	@echo "Creating the database..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:database:create --no-interaction'
	@echo "Running migrations..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:migrations:migrate --no-interaction'
	@echo "Loading test data..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:fixtures:load --no-interaction'

phpstan: ##Runs PHPStan
	@echo "Analyzing code with PHPStan..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && vendor/bin/phpstan analyse'

deploy: test phpstan## Runs before deployment

test: ## Runs tests
	@echo "Running PHPUnit tests..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && vendor/bin/phpunit'
