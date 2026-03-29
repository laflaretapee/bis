
Для запуска необходимо создать три .env файла, структура которых приведена ниже и в файле .env.template

```bash
### .env.mysql
MYSQL_DATABASE=db
MYSQL_USER=user
MYSQL_PASSWORD=pwd
MYSQL_ROOT_PASSWORD=rootpwd

### .env.pma
PMA_HOST=mysql
PMA_PORT=3306
PMA_USER=user
PMA_PASSWORD=rootpwd

### .env.wordpress
WORDPRESS_DB_HOST=mysql:3306
WORDPRESS_DB_USER=user
WORDPRESS_DB_PASSWORD=pwd
WORDPRESS_DB_NAME=db
```

После настройки переменных окружения необходимо собрать контейнеры.

```bash
docker compose up
```

| Приложение | Адрес            |
| ---------- | ---------------- |
| Wordpress  | `localhost:8000` |
| phpMyAdmin | `localhost:8080` |
| MySQL      | `localhost:3307` |

Далее необходимо авторизоваться в WordPress `localhost:8000` и установить соответствующую тему. Чтобы импортировать данные достаточно после авторизации удалить все таблицы из БД и импортировать файл дампа БД из phpMyAdmin. Важно не делать дамп, до установления Wordpress!

После дампа и установки темы нужно в настройках 'Постоянные ссылки' установить 'Название записи'.