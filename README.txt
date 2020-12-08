
Тестовое задание "Подписки mailchamp".  Установка проекта.

Предполагается что проект будет установлен на OC Линукс. И в системе уже установлен lamp и composer.

1. Установка чистого laravel проекта

Необходимо установить на своем сервере новый проект laravel 5.8

Пускай директория проекта будет /var/www/subscribe

открываем консоль, переходим в директорию /var/www/:
cd /var/www/

устанавливаем laravel:
composer create-project --prefer-dist laravel/laravel subscribe "5.8.*"

устанавливаем необходимые права на директории:
sudo chmod 777 -R /var/www/subscribe/storage
sudo chmod 777 -R /var/www/subscribe/bootstrap/cache


создаем базу данных subscribe (например с помощью phpmyadmin)


после создания базы данных в проекте открываем файл /var/www/subscribe/.env
нужно указать правильные значения для DB_DATABASE, DB_USERNAME, DB_PASSWORD

в моем случае это:
    DB_DATABASE=subscribe
    DB_USERNAME=root
    DB_PASSWORD=123456


Обновим композером миграции. В консоли в директории проекта /var/www/subscribe вводим:
php artisan migrate

- в этот момент в базе данных subscribe должны были появиться таблицы:  migrations, password_resets, users


Запуск сайта на сервере.
Можно настроить на сервере виртуальный сервер, а можно и просто в отдельном окне консоли из директории проекта запустить:
php artisan serve

- после запуска процесса открываем свой браузер и переходим на страницу http://localhost:8000/
Должна открываться страница "Laravel с меню". Если надпись Laravel и меню не видны, то нужно разбираться
где ошибка.




2. Перенос/установка проекта subscribe

Открывайте новое окно консоли, переходите в директорию с web-проектами
cd /var/www/

скачайте файлы subscribe-проекта в себе в новую директорию:

git clone https://github.com/vlad13/subscribe-files.git

- в директории /var/www/subscribe-files должны были появиться файлы проекта.
Все эти ФАЙЛЫ перенесите вручную в директорию вашего проекта /var/www/subscribe.
Если какие-либо файлы совпадают (как например файл app/Console/Kernel.php), то смело заменяйте прежние файлы на файлы из директории subscribe-files.

Также скрытую директорию .git можно перенести из директории /var/www/subscribe-files
в /var/www/subscribe если необходимо будет выгружать изменения обратно в github.



далее выполните в консоли:
cd /var/www/subscribe
git pull origin main



в консоли снова переходим в проект:
cd /var/www/subscribe

устанавливаем пакет:
composer require mailchimp/marketing

применяем новую миграцию:
php artisan migrate
- в базе данных должна была появиться новая таблица user_subscribe

применяем новый сид:
php artisan db:seed --class=UsersSubscribesTableSeeder
- таблица user_subscribe должна была заполниться 10 записями


запустим локальный сервер от ларавел (если он не запушен):
php artisan serve

в браузере на странице http://localhost:8000/ должна отображаться стандартная страница Laravel "из коробки"
+ в правом верхнем углу ссылки: Login и Register.
Зарегистрируйте своего нового пользователя.

Далее после регистрации/авторизации откройте снова главную страницу http://localhost:8000/
Там в правом верхнем углу должны быть ссылки: HOME  ADD SUBSCRIBER  LOGOUT
(если ссылки ADD SUBSCRIBER  LOGOUT не видны, то это кеш браузера, сбрасывайте кеш)

Перейдя по ссылке "Add subscriber" можно добавить новых подписчиков в таблицу user_subscribe




3. Настройка обмена данными с mailchimp

в проекте в файле /var/www/subscribe/.env создайте 3 новых переменных:
MAILCHIMP_APIKEY=5b2b914c0be17096dff4894a763a1de6
MAILCHIMP_SERVER=us2
MAILCHIMP_LIST_ID=3246201174

Используйте свои apikey, server и list_id

Получить эти данные можно зарегистрировашись на сайте mailchimp.com
(думаю эту частьне нужно описывать, поэтому не пишу)


-----

Перенос существующих или новых подписчиков из своего проекта в mailchamp список.

Перенесети ваших подписчиков из таблицы user_subscribe в mailchamp. Запустите в консоли команду:
php artisan send-subscribers

- после выполнения этой команды в mailchamp в вашем списке появяться новые подписчики.

-----

Настройка переноса статуса "подписан"/"отписан" из своего проекта в mailchamp.



На вашем сервере крон нужно настроить на вызов расписаний ларавела, для этого откройте окно заданий крона из консоли линукса:
crontab -e

введите там такую строку:
* * * * * php /var/www/emailing_test/artisan schedule:run >>/dev/null 2>&1

и сохраните файл. Теперь расписания ларавел будут "сами" запускаться в нужное время.


В проекте в файле /var/www/subscribe/app/Console/Kernel.php
в функции schedule() уже установлено на запуск задание. Либо так написано:
$schedule->command('send-subscribers-changes')->everyMinute();
    - каждую минуту запускается

Либо так написано:
$schedule->command('send-subscribers-changes')->everyThirtyMinutes();
    - каждые 30 минут написано

Эта команда запускает периодически консольный скрипт находящийся в файле
/var/www/subscribe/app/Console/Commands/SendSubscribersChanges.php
- этот скрипт статус подписчиков "подписан" или "не подписан" переносит из текущего проекта в mailchamp-список.

Попробуйте в базе данных в таблице user_subscribe изменить состояние подписки одного из подписчиков (установите 1 или 0 для поля is_subscribed)
и просто подождите 1 минут (или 30 минут :) ), после этого в mailchamp в списке существующих контактов у данного подписчика изменится
статус подписки.
