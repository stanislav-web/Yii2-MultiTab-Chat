# Yii 2 Online Ajax Chat

### Описание
Простенький чат, для общения нескольких человек (не ограничено).
После отправки сообщения пользователь привязывается по IP к сессии и базе данных
Есть ограничение в плане общения с разными никами под IP. - Одни ник - Один IP

### Разработка 
- [CONFIGURE] Docker compositor for Nginx 1.3 + PHP 5.6 + MySQL 5.6 @estimated 1h
- [CONFIGURE] Yii2 & change docker mysql for self import  @estimated 30m
- [CONFIGURE] Bootstrap 3 template @estimated 30m
- [ADD] Ajax saving chat actions into DB @estimated 3h
- [COMPLETE] Ajax response and handling list @estimated 3h

### Требования
MySQL 5.5
PHP5.6 FPM
NGINX

### Установка

***1.*** Через Docker
```
docker-compose up
```

***2.*** Обычным способом 
```
(настроить Nginx на точку входа ROOT/app/web/index.php)

> cd app/
> composer install

(создать базу и загрузить схему /var/www/chat/data/init/schema.sql)
настройки подключения в > app/config/db.php
```

### Запуск

```
(если установлено из докера)
http://localhost:8000

иначе, по вашему хосту
```

Total : 8h