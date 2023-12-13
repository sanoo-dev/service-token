# Deploy production

## Domain
```https://sso.tuoitre.vn```

## Server requirements:

- nginx
- php >= 8.0
- percona >= 8.0
- redis

## Connection

Open connect to server:

- New Service Member (Mr.Phong):
  - Domain: https://api-member.tuoitre.vn
  - Ip: *
  - Port:80, 443
- Server Sentry:
  - Domain: https://sentry.tuoitre.com.vn
  - Channel: * (Ex: https://a4c1547e403842b5b5c1e6b2ac236650@sentry.tuoitre.com.vn/17)
- Server Percona (if installed on another server):
  - Ip: *
  - Port: 3306
- Server Redis (if installed on another server):
  - Ip: *
  - Port: 6379

## Setup

1. Create new database with name: `member_sso`
    ```
    CREATE DATABASE member_sso CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
    ```

2. Clone code from git
    ```
    cd /var/www/html/ (or to your WORKDIR)
    git clone git@gitlab.tuoitre.com.vn:services/member_sso.git member_sso
    cd member_sso
    ```
3. Extract vendor from `vendor.tar.xz` to vendor folder
    ```
    sudo tar -xvf vendor.tar.xz
    ```

4. Setup ENV:
    - Copy .env.example to .env
    - Edit file .env following config:
      ```
        #=====Project config=====
        APP_ENV=production
        APP_NAME=member-sso
        APP_KEY=base64:6Vw8RtnGlb2W1Q4qT+sDdSRv5QFzSed5QUrAJ2hM6WA=
        APP_DEBUG=true
        APP_URL=https://sso.tuoitre.vn
        REDIRECT_HTTPS=true
        DEBUGBAR_ENABLED=true
        SESSION_COOKIE=SSO_SID

        #=====Log config=====
        LOG_CHANNEL=stack
        LOG_DEPRECATIONS_CHANNEL=null
        LOG_LEVEL=warning

        #=====Database config=====
        DB_CONNECTION=mysql
        DB_HOST=localhost
        DB_PORT=3306
        DB_DATABASE=member_sso
        DB_USERNAME=root
        DB_PASSWORD=8r3euXlZls

        BROADCAST_DRIVER=log
        CACHE_DRIVER=redis
        FILESYSTEM_DRIVER=local
        QUEUE_CONNECTION=sync
        SESSION_DRIVER=redis
        SESSION_LIFETIME=120

        MEMCACHED_HOST=127.0.0.1

        #=====Redis config=====
        REDIS_HOST=127.0.0.1
        REDIS_PASSWORD=null
        REDIS_PORT=6379
        REDIS_PREFIX=db_sso_
        REDIS_DB=4
        REDIS_CACHE_DB=5
        REDIS_SESSION_DB=6

        #=====CORS config=====
        CORS_PATHS=sso/*,api/*
        CORS_ORIGINS=*.tuoitre.vn,*.tuoitre.com.vn

        #=====SSO Broker config=====
        SSO_BROKER_COOKIE_NAME=_ttsid
        SSO_BROKER_COOKIE_EXPIRED_TIME=1440
        SSO_BROKER_COOKIE_DOMAIN=.tuoitre.vn
        SSO_SERVER_URL=https://sso.tuoitre.vn/sso/v1/
        SSO_BROKER_NAME=beta-client
        SSO_BROKER_SECRET_KEY=A46MhQOxEHOfQy7BkuqpVB1ZGRcRtWXgD7EmV04QsfOzJZ3sCY066vbUJGOfneqG
        SSO_BROKER_PUBLIC_KEY=oQt5MjeoVNokTWVmdUu0bUPNd7HPDkXrtCtChMBnqLVdS8ck
        SSO_SESSION_INFO=s_info

        #=====SSO Server config=====
        SSO_API_VERSION=v1
        SSO_URL_PREFIX = sso
        SSO_SESSION_NAME=_sso
        SSO_MAX_ATTEMPTS_PER_MINUTE=300
        SSO_BROKER_DEFAULT_SESSION_EXPIRED_MINUTES=10
        SSO_BROKER_DEFAULT_SESSION_REMEMBER_LOGIN_EXPIRED_MINUTES=240
        SSO_BROKER_DEFAULT_KEEP_TOKEN_AFTER_EXPIRED_MINUTES=1440
        MEMBER_SERVER_URL=https://api-member.tuoitre.vn/v1/member/

        #=====Sentry config=====
        SENTRY_LARAVEL_DSN=https://a4c1547e403842b5b5c1e6b2ac236650@sentry.tuoitre.com.vn/17
        SENTRY_TRACES_SAMPLE_RATE=1.0
        SENTRY_LOG_LEVEL=error
       ```
    - Note:
      - Broker information (edit these values after **step 6** is done):
        - SSO_BROKER_NAME=example_broker_name
        - SSO_BROKER_SECRET_KEY=example_broker_secret_key
        - SSO_BROKER_PUBLIC_KEY=example_broker_public_key

5. Run migration:
    ```
    php artisan migrate --path=packages/laravel-sso/database/migrations/
    ```

6. Create broker: (Create new every time there is a new site that needs to use SSO)
    ```
    php artisan laravel-sso:create-broker example_broker_name example_domain_broker.tuoitre.vn
    ```
    - After create success, provide the site with the broker's information include broker_name, broker_secret_key, broker_public_key

    - With this project, after creating a new broker,
   change the value of ENV keys `SSO_BROKER_NAME, SSO_BROKER_SECRET_KEY, SSO_BROKER_PUBLIC_KEY`
   corresponding to: `broker_name, broker_secret_key, broker_public_key`

7. Config nginx:

    Example config:
    ```
    server {
        listen 80;

        client_max_body_size 136M;
        server_name  sso.tuoitre.vn;

        access_log  /var/log/nginx/member_sso_access.log;
        error_log /var/log/nginx/member_sso_error.log error;

        root /var/www/html/member_sso/public;
        index index.php;

        # try to serve file directly, fallback to index.php
        location / {
            try_files $uri /index.php$is_args$args;
        }

        if (!-e $request_filename) {
            rewrite ^.*$ /index.php last;
        }

        location ~ ^/storage/(.*) {
            return 404;
        }

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php-fpm/www.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param PHP_VALUE "error_log=/var/log/nginx/application_php_errors.log";
           # fastcgi_buffers 16 16k;
            fastcgi_buffer_size 32k;
            include fastcgi_params;
        }
    }
    ```
