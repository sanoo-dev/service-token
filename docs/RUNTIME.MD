## Every time there is a new site that needs to use SSO Server

- Run command create new broker for new site (change value of `example_broker_name` and `example_domain_broker.tuoitre.vn`)
    ```
    php artisan laravel-sso:create-broker example_broker_name example_domain_broker.tuoitre.vn
    ```
- After create success, provide the site with the broker's information include broker_name, broker_secret_key, broker_public_key

Example broker's information:
- **broker_name**: beta-client
- **broker_public_key**: A46MhQOxEHOfQy7BkuqpVB1ZGRcRtWXgD7EmV04QsfOzJZ3sCY066vbUJGOfneqG
- **broker_public_key**: oQt5MjeoVNokTWVmdUu0bUPNd7HPDkXrtCtChMBnqLVdS8ck
