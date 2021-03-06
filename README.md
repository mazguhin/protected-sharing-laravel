## Описание

Пример приложения для отправки текстовой информации с использованием двух каналов связи. 
Первый канал (определяется пользователем) служит для отправки идентификатора, второй — для отправки ключа, с помощью которого получатель сможет расшифровать сообщение.
Данных подход обеспечивает большую надежность в случае, когда один из используемых каналов скомпрометирован.

## Разработка

**Запуск**
```bash
$ docker-compose -f docker-compose.local.yml up --build 
```

**Установка зависимостей**
```bash
$ docker-compose -f docker-compose.local.yml run composer install 
```

**Запуск миграций**
```bash
$ docker-compose -f docker-compose.local.yml run artisan migrate 
```

**PHPStan**
```bash
$ docker-compose -f docker-compose.local.yml run php ./vendor/bin/phpstan analyse --memory-limit=2G
```

**PHPCS**
```bash
$ docker-compose -f docker-compose.local.yml run php ./vendor/bin/phpcs
```

**PHPCBF**
```bash
$ docker-compose -f docker-compose.local.yml run php ./vendor/bin/phpcbf
```
