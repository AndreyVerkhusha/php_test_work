# README

## Описание проекта

Данный проект представляет собой RESTful API сервис, разработанный на фреймворке Laravel версии 12 с использованием PHP версии 8.2. Сервис предоставляет функционал для управления пользователями, включая регистрацию, авторизацию и операции с пользователями, такие как редактирование, удаление и восстановление.

## Функционал

### Публичные маршруты
Доступны всем неавторизованным пользователям:
- **Регистрация пользователя**: Позволяет пользователям создавать новый аккаунт.
- **Авторизация пользователя**: Позволяет пользователям входить в систему.

### Приватные маршруты
Доступны только после авторизации пользователя:
- **Просмотр списка пользователей**: Возвращает список всех зарегистрированных пользователей.
- **Просмотр пользователя**: Позволяет получить информацию о конкретном пользователе.
- **Редактирование пользователя**: Позволяет обновить информацию о пользователе.
- **Удаление пользователя в корзину**: Удаляет пользователя, помещая его в корзину.
- **Просмотр списка пользователей в корзине**: Позволяет увидеть всех удаленных пользователей.
- **Восстановление пользователя из корзины**: Позволяет восстановить пользователя, ранее удаленного в корзину.
- **Полное удаление пользователя из БД**: Удаляет пользователя из базы данных навсегда.
- **Групповое удаление пользователей в корзину**: Позволяет удалить нескольких пользователей, помещая их в корзину.
- **Групповое удаление пользователей из БД**: Позволяет навсегда удалить нескольких пользователей.
- **Групповое восстановление пользователей из корзины**: Позволяет восстановить нескольких пользователей из корзины.

### Логирование
Все действия с пользователями логируются в базе данных в таблице `histories`.

## Технические требования

- Сервис написан с использованием фреймворка Laravel версии 12.
- Используется PHP версии 8.2.
- Применен подход тонких контроллеров для улучшения структуры кода.
- Код максимально декомпозирован, с выделением повторяющегося кода в трейты и абстрактные классы.
- В качестве идентификатора записи в базе данных используется строковое представление UUID6.
- Redis используется для кеширования объектов и снижения нагрузки на базу данных.
- Написаны фабрики для наполнения базы данных тестовыми (фейковыми) данными.
- Бизнес-логика вынесена в сервисный слой для улучшения читаемости и поддержки кода.
- Ответы от сервиса возвращаются в формате JSON, также реализована возможность возврата в формате XML.
- Реализована возможность фильтрации и сортировки при просмотре списка пользователей по всем полям таблицы.
- Использованы встроенные библиотеки фреймворка для упрощения разработки.
- Загружен файл Test_work_users.postman_collection для быстрого взаимодействия через программу Postman.
 
## Реализованные требования

### Само-документируемый код
В проекте реализован само-документируемый код с использованием тайпхинтов и стандартов PSR. Каждый метод и класс снабжен четкими аннотациями типов.

### Чистота и качество кода
Особое внимание уделено чистоте и качеству кода, следуя принципам SOLID и используя статический анализ кода.

### Контейнеризация проекта
Проект контейнеризирован с использованием Docker, что позволяет легко и быстро развернуть приложение в любых средах. Созданы Dockerfile и docker-compose.yml, которые обеспечивают автоматическую настройку всех необходимых сервисов, таких как база данных и веб-сервер.

### Применение паттернов DRY, KISS, YANGI
В процессе разработки следовал принципам DRY (Don't Repeat Yourself), KISS (Keep It Simple, Stupid) и YANGI (You Aren't Gonna Need It).

### Ведение документации в Swagger
Документация API ведется с использованием Swagger, что позволяет разработчикам и пользователям легко ознакомиться с доступными эндпоинтами, их параметрами и ответами. Swagger UI интегрирован в проект, что обеспечивает интерактивный доступ к документации.

### Форматирование кода
Для форматирования кода используется библиотека `laravel/pint`, что обеспечивает единообразие стиля кода по всему проекту. Это упрощает чтение и поддержку кода, а также способствует соблюдению стандартов оформления.

## Установка

### Клонировать репозиторий:

```bash
git clone https://github.com/AndreyVerkhusha/php_test_work.git
cd php_test_work
```

### Установить зависимости:

```bash
composer install
```

### Файл .env:

```bash
убрал .env из .gitignore для удобства.
```

### Запустить миграции и заполнить базу данных тестовыми данными:

```bash
php artisan migrate --seed
```

### Запустить сервер:

```bash
php artisan serve
```

Теперь API доступно по адресу [http://127.0.0.1:8000](http://127.0.0.1:8000). </br>
Swagger доступен по адресу [http://127.0.0.1:8000/swagger](http://127.0.0.1:8000/swagger).

## Запуск с использованием Docker

Для запуска приложения в контейнерах Docker выполните следующие шаги:

1. Убедитесь, что Docker и Docker Compose установлены на вашем компьютере.

2. Соберите и запустите контейнеры:

   ```bash
   docker-compose up -d
   ```

3. После успешного запуска контейнеров, API будет доступно по адресу [http://localhost:8080](http://localhost:8080).
   
5. Swagger будет доступен по адерсу [http://localhost:8080/swagger](http://localhost:8080/swagger)

6. Для доступа к базе данных через phpMyAdmin, перейдите по адресу http://localhost:8081.

### Описание контейнеров

Docker поднимает следующие контейнеры:

- **app**: Контейнер с приложением Laravel. Строится на основе Dockerfile и монтирует текущую директорию в `/var/www`.

- **web**: Контейнер с Nginx, который обрабатывает HTTP-запросы. Он монтирует текущую директорию и конфигурационный файл Nginx, а также проксирует запросы на порт 8080.

- **db**: Контейнер с MySQL версии 5.7. Он хранит данные в томе `db_data` и использует переменные окружения для настройки базы данных и пользователя.

- **phpmyadmin**: Контейнер с phpMyAdmin для управления базой данных. Доступен по порту 8081 и подключается к контейнеру MySQL.

Все контейнеры находятся в одной сети `laravel-network`, что позволяет им взаимодействовать друг с другом.

