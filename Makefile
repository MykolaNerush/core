export CONTAINER_PHP=$(shell docker ps -aqf "name=php8_3")
export WORK_DIR="/var/www/php/core.lc"
DOCKER_EXEC=docker exec -it $(CONTAINER_PHP) bash -c

# Styling for output
COLOR_RESET=\033[0m
COLOR_HEADER=\033[36m
COLOR_DESCRIPTION=\033[33m

help: ## Displays the list of available commands
	@printf "$(COLOR_HEADER)Available commands:$(COLOR_RESET)\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  $(COLOR_HEADER)%-20s$(COLOR_RESET) $(COLOR_DESCRIPTION)%s$(COLOR_RESET)\n", $$1, $$2}'

drop_db: ## Drops the database
	@echo "Dropping the database..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:database:drop --force --no-interaction'

install: ## Installs the project (rerun with drop_db for reinstallation)
	@echo "Installing dependencies via Composer..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && composer install'
	@echo "Creating the database..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:database:create --no-interaction'
	@echo "Running migrations..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:migrations:migrate --no-interaction'
	@echo "Loading test data..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && bin/console doctrine:fixtures:load --no-interaction'

deploy: ## Runs code analysis before deployment
	@echo "Analyzing code with PHPStan..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && vendor/bin/phpstan analyse'

reset: drop_db install ## Completely reinstalls the project (DB + dependencies)
	@echo "The project has been reinstalled!"

test: ## Runs tests
	@echo "Running PHPUnit tests..."
	$(DOCKER_EXEC) 'cd $(WORK_DIR) && vendor/bin/phpunit'
