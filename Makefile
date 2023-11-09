EXEC_PHP := docker-compose exec -it php

up: ## Запуск контейнеров
	docker-compose up --detach --remove-orphans
.PHONY: up

down: ## Остановка контнейнеров
	docker-compose down --remove-orphans
.PHONY: down

restart: down up## Перезапуск контейнеров
.PHONY: restart

init: ## Создание таблицы в ClickHouse
	$(EXEC_PHP) bin/cake init_click_house_table
.PHONY: php

drop: ## Удаление таблицы в ClickHouse
	$(EXEC_PHP) bin/cake drop_click_house_table
.PHONY: php

php: ## Войти в контейнер php
	$(EXEC_PHP) sh
.PHONY: php

test: ## Запустить тесты
	vendor/bin/phpunit
.PHONY: test
