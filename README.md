
Для запуска необходимо создать три .env файла, структура которых приведена ниже и в файле .env.template

```bash
### .env.mysql
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=
MYSQL_ROOT_PASSWORD=

### .env.pma
PMA_HOST=
PMA_PORT=
PMA_USER=
PMA_PASSWORD=

### .env.wordpress
WORDPRESS_DB_HOST=
WORDPRESS_DB_USER=
WORDPRESS_DB_PASSWORD=
WORDPRESS_DB_NAME=
```

После настройки переменных окружения необходимо собрать контейнеры.

```bash
docker compose up --build
```

| Приложение | Адрес            |
| ---------- | ---------------- |
| Wordpress  | `localhost:8000` |
| phpMyAdmin | `localhost:8080` |
| MySQL      | `localhost:3307` |