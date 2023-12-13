# SERVER REQUIREMENTS:

- nginx
- php >= 8.0
- percona >= 5.7
- redis

# INSTALL:

- Pull code from server git:

  ```bash
    cd /var/www/html/ (or to your WORKDIR)
    git clone git@gitlab.tuoitre.com.vn:services/member_sso.git project_name
    cd project_name
  ```

- Copy .env.example to .env and change value of APP_ENV (local, dev, production)
- Copy env/.env_local.example to .env_local and edit values to suit your environment

- Setup docker: You can use your docker and edit config file (nginx,etc...)
  or:
    - Copy folder **/project_name/docker-example to new folder **/project_name/docker
    - Copy file **/project_name/docker-compose.example.yml to new file: **/project_name/docker-compose.yml
    - Copy edit config paths in file docker-compose.yml, file config nginx (**/project_name/docker/nginx/nginx.conf).
    - Run ```docker-composer up```.

- Setup project:
    - Access to docker php (example the container_name of php is "mem_sso_php80")
        - ```docker exec -it mem_sso_php80 bash```
    - Cd to project_name
        - ```cd /var/www/html/project_name```
    - Run command to install composer (vendor)
        - ```composer install -vvv```
        - You can extract vendor.tar.xz if run install command take too much time

    - Create and chmod folders: (if an error is encountered)
        ```
        cd /var/www/html/project_name

        mkdir storage/framework/cache
        mkdir storage/framework/sessions
        mkdir storage/framework/views

        chmod -R o+w storage/framework/
        chmod -R o+w storage/logs/
        chmod -R o+w bootstrap/cache/
      ```
    - Migrate database:
        * For sso server:
          ```
          php artisan migrate --path=packages/laravel-sso/database/migrations/
          ```

    - Create example broker:
      ```
      php artisan laravel-sso:create-broker example_broker_name example_domain_broker.tuoitre.vn
      ```
    - Occasionally, you may need to run command to clear cache in laravel
  (cache package manifest, cache view, cache config, cache route,...)
  to avoid errors during the update process (ex: add new or update package, vendor,... )
      ```
      php artisan package:discover
      php artisan optimize:clear
      php artisan route:clear
      php artisan view:clear
      ```

# NOTE

Setup docs may have shortcomings, if any error occurred, please contact me [quocdajr@gmail.com](#) for more details. Good luck!

# CONTRIBUTES

You can help me improve this document. Thank you!
