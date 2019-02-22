ERC-MAP
=======

## Stack and libraries used

* PHP 7.3
* PostgreSQL 9.6 with PostGIS 2.4
* Symfony 4.2 with Encore build system
* Bootstrap 4.2
* jQuery 3
* [ChosenJS for jQuery](https://harvesthq.github.io/chosen/) (autocomplete combo box)
* [DataTables for jQuery](https://datatables.net/) (improved data tables)

## Installation notes

* Create a PostgreSQL database and set the DSN connection string in the required environment variable : `DATABASE_URL=pgsql://[USER]:[PASSWORD]@[HOST]:[PORT]/[DATABASE_NAME]`
* Create another environment variable containing the environment : `APP_ENV=[prep|prod]` (`prep` is like `prod` but with debug mode enabled)
* Run the following commands at the root of your project :
```bash
    # Install prerequisites
    composer install --no-dev

    # Apply migrations and load seed data
    php bin/console doctrine:migrations:migrate --no-interaction
```
* Start (or restart) web server