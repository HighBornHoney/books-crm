# Book CRM

## Локальное развертывание для разработки

- Клонировать репозиторий `git clone https://github.com/HighBornHoney/books-crm.git`
- Перейти в директорию с проектом `cd books-crm`
- Создать файл .env с переменными окружения из .env.example `cp .env.example .env`
- Запустить docker контейнеры `docker compose up -d`
- Установить зависимости проекта `docker compose exec php composer install`
- Выполнить миграции `docker compose exec php yii migrate`
