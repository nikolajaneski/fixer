
## GIGS API

RESTfull API for converting currencies platform build in Laravel using Laravel Passport for authentication and Fixer for daily rates :


## Instructions for starting API on local env

Clone project 
```
git clone https://github.com/nikolajaneski/fixer.git projectName
```

CD into your project

```
cd projectName
```

Install Composer dependencies

```
compser install
```

Create copy of .env file and add DB information for connection
```
cp .env.example .env
```

Generate app encryption key
```
php artisan key:generate
```

Run DB migrations
```
php artisan migrate
```

Install Laravel Passport
```
php artisan passport:install
```

Finaly run the application
```
php artisan serve
```


## Available API routes

POST register - Register new user

POST login - Login and get access token

POST convert - convert currency 
    - fields : from, to, value, token

