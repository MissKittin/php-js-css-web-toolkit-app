# Application assets
1. To install assets for example project, run in parent directory:

		php ./app/bin/install-assets.php

	and follow the prompts  
	or you can use inline assets (but this is not recommended)

		export APP_INLINE_ASSETS=yes

2. Compile assets to the public directory:

		php ./tk/bin/assets-compiler.php ./app/assets ./public/assets


# Materialized template
To enable the materialized template for components, set the environment variable:  
```
export APP_MATERIALIZED=yes
```


# Application content

### Routes
* `about.php` - `/about` (About toolkit) (has view)
* `check-date.php` - `/check-date` (`check_date()` test) (has view)
* `database-test.php` - `/database-test` (Database libraries test) (has view)
* `home.php` - `/` (has view)
* `http-error-test.php` - `/http-error-test` (HTTP errors) (404 page) (has view)
* `login-component-test.php` - `/login-component-test` (Login component test) (has view)
* `login-library-test.php` - `/login-library-test` (Login library test) (has view)
* `obsfucate-html.php` - `/obsfucate-html` (HTML obsfucator test) (has view)
* `phar-test.php` - `/phar-test` (Toolkit in Phar test) (has view)
* `preprocessing-test.php` - `/preprocessing-test` (PHP preprocessing test) (has view)
* `robots.php` - `/robots.txt`
* `sitemap.php` - `/sitemap.xml`
* `tk-test.php` - `/tk-test`
* `ws-test.php` - `/ws-test`

### Controllers <-> routes
* `check-date.php` <-> `check-date.php`
* `database-test.php` <-> `database-test.php`
* `http_error.php` <-> `http-error-test.php`
* `login-component-test.php` <-> `login-component-test.php`
* `login-library-test.php` <-> `login-library-test.php`
* `phar-test.php` <-> `phar-test.php`
* `preprocessing-test.php` <-> `preprocessing-test.php`
* `robots-sitemap.php` <-> `robots.php` `sitemap.php`
* `tk-test.php` <-> `tk-test.php`

### Models <-> routes
* `database_test_abstract.php` <-> `database-test.php`
* `login_component_test_credentials.php` <-> `login-component-test.php`
* `login_library_test_credentials.php` <-> `login-library-test.php`

### Databases (host:port db_name user password)
One of these databases is used in the `database_test_abstract.php` model.  
You can select a database via the `DB_TYPE` environment variable (default: `sqlite`).
* `pgsql` (`127.0.0.1:5432` `sampledb` `postgres` `postgres`)
* `mysql` (`[::1]:3306` `sampledb` `root` [no password])
* `sqlite` (`./var/lib/databases/sqlite/database.sqlite3`)

You can configure the database connection through the following environment variables:
* `PGSQL_HOST`
* `PGSQL_PORT`
* `PGSQL_SOCKET` (has priority over the host/port)
* `PGSQL_DBNAME`
* `PGSQL_CHARSET`
* `PGSQL_USER`
* `PGSQL_PASSWORD`
* `MYSQL_HOST`
* `MYSQL_PORT`
* `MYSQL_SOCKET` (has priority over the host/port)
* `MYSQL_DBNAME`
* `MYSQL_CHARSET`
* `MYSQL_USER`
* `MYSQL_PASSWORD`
* `SQLITE_PATH`
* `DB_IGNORE_ENV=true` (ignores all the variables above and the `DB_TYPE`)

### Cache databases
You can configure the database connection through the following environment variables:
* `MEMCACHED_HOST` (default: `127.0.0.1`)
* `MEMCACHED_PORT` (default: `11211`)
* `MEMCACHED_SOCKET` (has priority over the host/port)
* `MEMCACHED_IGNORE_ENV=true` (ignores all memcached variables above)
* `REDIS_PREDIS=true` (use Predis package, default: `false`)
* `REDIS_HOST` (default: `127.0.0.1`)
* `REDIS_PORT` (default: `6379`)
* `REDIS_SOCKET` (has priority over the host/port)
* `REDIS_DBINDEX` (default: `0`)
* `REDIS_IGNORE_ENV=true` (ignores all redis variables above)

### Libraries
* `stdlib.php` - application standard library
* `app_template.php` - default http headers and a basic template overlay that saves typing
* `logger.php` - logging functions
* `ob_adapter.php` - `ob_start()` handler
* `ob_cache.php`
* `pdo_instance.php` - get PDO handler
* `session_start.php` - session handler

### Components
* `basic_template` - simple template management

### Tools
* `install-assets.php`
* `remove-samples.php`
* `replace-public-index-with-link.php`
* `session-clean.php` - remove stale sessions (if the application stores the session content in files)
