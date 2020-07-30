# Развертывание проекта

Требования:

<ul>
    <li>PHP >= 7.2</li>
    <li>PostgreSQL >= 9.6</li>
</ul>

Этапы развёртывания:
   ## Backend
   - Выполнить команду установки зависимостей
   ```
   composer install
   ```
  
   - Скопировать файл <strong>.env.example</strong> в <strong>.env</strong> в корне проекта
   
   - Создать базу данных PostgreSQL, прописать доступы к базе в файле <strong>.env</strong>
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_DATABASE=bgs_group
   DB_USERNAME=bgs_group
   DB_PASSWORD=bgs_group
   ``` 
   - Выполнить миграции таблиц БД
   ```
   php artisan migrate
   ```
   
   - Создать уникальный ключ приложения
   ```
   php artisan key:generate
   ```
   - Вписать в .env токен для авторизации по api
   ```
   API_TOKEN=wjMHcrk2HnJyjgaKJ5TMwN9fnVXicmjNv2TdXEKM9TludgIvGvx2kQmGM6MAhdos
   ```

