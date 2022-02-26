
# Expence App

 An expense sharing application
is where you can add your expenses and split it among different people. The app keeps
balances between people as in who owes how much to whom.

#### Laravel 9.x

## Installation

## Installation

```bash
git clone https://github.com/hiteshkr1995/expence-app-unico.git
```

```bash
cd expence-app-unico
```

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

After this please set the database credentials in .env we use MYSQL.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expence_app
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate
```

#### To run project
```bash
php artisan serve
```
## Postman Collection Instruction

In mail you find Postman Collection json file. please import in the Postman app.

- Create envirnonment with two variable
    - baseUrl
    - authToken

- Once you register or login that Collection is binded properly so it work with auth token and base url.

### Overview
In the postman Collection you find two folder.
  -  One is for user
      -  User can (register, login, logout, expence)
      -  And one API for list user's.
  -  Second is for store expence

#### In Expence store API please use raw data like mention below:

For EQUAL
```bash
  {
    "amount": 1000,
    "type": 1,
    "user_ids": [2, 3, 4]
  }
```

For EXACT
```bash
  {
    "type": 2,
    "users": [
        {
            "id": 2,
            "amount": 370
        },
        {
            "id": 3,
            "amount": 880
        }
    ]
  }
```

For PERCENT
```bash
  {
    "amount": 1200,
    "type": 3,
    "users": [
        {
            "id": 1,
            "percent": 40
        },
        {
            "id": 2,
            "percent": 20
        },
        {
            "id": 3,
            "percent": 20
        },
        {
            "id": 4,
            "percent": 20
        }
    ]
  }
```
