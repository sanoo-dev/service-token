# Auto Deploy

- Git: `git@gitlab.tuoitre.com.vn:services/member_sso.git`
- Dir: `/var/www/html/member_sso`
- Brand: `master`
- Service restart: `php-fpm`

#### NOTE: When vendor is updated

- Extract vendor from `vendor.tar.xz` to vendor folder
    ```
    sudo tar -xvf vendor.tar.xz
    ```
- Run command clear cache bootstrap files, packages manifest
    ```
    php artisan optimize:clear
    php artisan package:discover
    ```
- Run command restart php if necessary
    ```
    sudo service php-fpm reload
    ```

#### NOTE: When database is updated

- Run migration:
    ```
    php artisan migrate --path=packages/laravel-sso/database/migrations/
    ```
