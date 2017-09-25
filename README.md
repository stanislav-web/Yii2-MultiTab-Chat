# Yii 2 Online Ajax Chat

### Описание
Простенький чат, для общения нескольких человек (не ограничено).
Чтобы проверить работу, нужно естественно установить, и открыть несколько вкладок в браузере для общения

![Alt text](/screnshoots/1.jpg "Optional Title")
![Alt text](/screnshoots/2.jpg "Optional Title")

### Разработка 
- [CONFIGURE] Docker compositor for Nginx 1.3 + PHP 5.6 + MySQL 5.6 @estimated 1h
- [CONFIGURE] Yii2 & change docker mysql for self import  @estimated 30m
- [CONFIGURE] Bootstrap 3 template @estimated 30m
- [ADD] Ajax saving chat actions into DB @estimated 3h
- [COMPLETE] Ajax response and handling list @estimated 3h
- [REDESIGN] Small markup changes @1h

### Требования
- MySQL 5.5
- PHP5.6 FPM / PHP 7 FPM
- NGINX

### Установка

***1.*** Через Docker
```
> docker-compose up

// при необходимости зайти в контейнер mysql и инмпортировать дамп
> (docker exec -i -t mysql /bin/bash)
> (mysql -uroot -p chat < /docker-entrypoint-initdb.d/schema.sql)
```
Если не получается запустить, поразным причинам то всегда можно поставить обычным способом (с Докером работаю не долго, поэтому пока на данный момент не совсем углублен в переносах контейнеров и возможно что то может пойти не так)

***2.*** Обычным способом 
```
(настроить Nginx на точку входа ROOT/app/web/index.php)

> cd app/
> composer install

(создать базу и загрузить схему /var/www/chat/data/init/schema.sql)
настройки подключения в > app/config/db.php

Проверить права на запись директорий
app/runtime/
app/web/assets/
```

### Запуск

```
(если установлено из докера)
http://localhost:8000

иначе, по вашему хосту
```

Total : 8h
