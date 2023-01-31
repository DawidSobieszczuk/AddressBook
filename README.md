## Instalacja

```bash
composer install
```

Utwórz plik .env. Poniżej przykładowy plik konfiguracyjny.

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbname
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

---

Wygeneruj app key

```bash
php artisan key:generate
```
---

Migracja bazy danych\
W bazie zostaną utworzone przykładowe adresy i trzech użytkowników. Jeden z rolą administratora.

```bash
php artisan migrate --seed
```

Dane do logowania

```
username: admin
password: password
```

```
username: user1
password: password
```
```
username: user2
password: password
```

---
Frontend

```bash
npm install
```

```bash
npm run build
```

## Inne
Do utworzenia nowego uzytkownika można użyć komendy

```bash
php artisan user:create
```
