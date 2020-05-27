Токен на клонирование ckeditor в laravel/storage/: git remote add origin 008134684038f0445bc8a0fa6af6452909fbfff2@github.com:igo4ek/ckeditor.git

Локальный сервер выбираем сами, настройкой mysql занимаемся сами.

Клонировать себе содержимое:
$ git clone https://<token>@github.com/igo4ek/laravel.git
[токен запрашивается у владельца репозитория. владелец генерирует токен тут: https://github.com/settings/tokens/]

Зайти в папку с проектом, копируем и настраиваем конфиг .env:
$ cd laravel; cp .env.example .env; nano .env

DB_DATABASE=fizika
DB_USERNAME=root
DB_PASSWORD=<пароль от вашей БД> // Как настроить пользователя mysql гуглим.
Генерить ключ и создать ссылку на хранилище:
$ php artisan key:generate; php artisan storage:link

Указать серверу папку laravel/public.
[тут уж в зависимости от самого сервера]

Импорт БД fizika.sql через adminer или php-my-admin.
[или создайте свою таблицу ‘fizika’]

Если ОС==Windows: в настройках IDE указываем путь к git.exe:
[например: C:\Program Files\Git\cmd\git.exe]

VCS->Git->Pull. Тут предложат добавить remote репозиторий, применяем этот:
https://<token>@github.com/igo4ek/laravel.git
[токен запрашивается у владельца репозитория. владелец генерирует токен тут: https://github.com/settings/tokens/]

Заходим в localhost – сайт должен работать на локальном сервере. Логинимся, проверяем.

// TODO: добавить сюда fizika.sql, в которую будет вшита пробная учетная запись администратора для демонстрации всех возможностей сайта.
