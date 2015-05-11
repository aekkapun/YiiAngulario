Yii2 + AngularJS Application
============================
Создание RESTful приложения теста по английскому языку.

Cистемные требования
--------------------
~~~
 * PHP >= 5.4+
 * MySQL >= 4.1+
 * Composer
 * Bower
~~~

Порядок установки
-----------------
1. Установить все необходимые зависимости для Backend части. Для этого нам понадобится Composer. Скачать его можно здесь: [Composer](https://getcomposer.org/ "Сomposer"). После чего необходимо будет скопировать файл `composer.phar` в директорию `backend`. После чего выполнить следующие команды.
~~~
 $ cd backend                     (Linux)
 cd backend                       (Windows)

 $ php composer.phar install      (Linux)
 php composer.phar install        (Windows)
~~~

2. Создать базу данных. 
> По умолчанию `angular-app`

3. Указать настройки доступа к БД. Файл конфигурации:
~~~
 backend/config/db.php
~~~

4. Сделать миграцию данных. Для этого выполните следующие команды
~~~
 $ ./yii migrate/up                (Linux)    
 yii migrate/up                    (Windows)
~~~

5. Установить все зависимости Frontend части. Для этого понадобится Bower. Всю информацию по установке можно получить по ссылке: [Bower](http://bower.io/#install-bower)
~~~
 $ cd frontend                     (Linux)
 cd frontend                       (Windows)

 $ bower install                   (Linux)
 bower install                     (Windows)
~~~

6. Указать путь для Backend части, с версией API, в конфигурации `frontend/app/src/app.js` в настройке `backend_url`, по умолчанию `http://localhost:8080/v1`.

7. Выполнить все настройки по домену, либо запустить проект под **PHP Development Server**.

PHP Development Server
-----------------
1. Для начала необходимо запустить сервер для Backend части. **Внимание:** указать необходимо тот же адрес, что был указан в настройке Frontend части.
~~~
 $ cd backend/web                   (Linux)
 cd backend\web                     (Windows)

 $ php -S localhost:8080            (Linux)
 php -S localhost:8080              (Windows)
~~~

2. Запустить сервер для Frontend части.
~~~
 $ cd frontend/app                  (Linux)
 cd frontend\app                    (Windows)

 $ php -S localhost:8000            (Linux)
 php -S localhost:8000              (Windows)
~~~

После чего можно будет в любом браузере открыть проект по ссылке `http://localhost:8000`

Cкриншоты
-----------------
![Login page](http://dl1.joxi.net/drive/0010/2551/719351/150511/d2989872aa.png)
![Dashboard](http://dl1.joxi.net/drive/0010/2551/719351/150511/cea1391ab2.png)
![Test](http://dl1.joxi.net/drive/0010/2551/719351/150511/4ebbd3dbbb.png)
![Result](http://dl2.joxi.net/drive/0010/2551/719351/150511/ebc1e99de3.png)
**Внимание:** Все вопросы и предложения можно присылать на lemon007@yandex.ru.
