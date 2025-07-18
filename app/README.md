# Application assets - new method
Run in parent directory:
```
php ./app/bin/samples/build-app.php
```
and serve:
```
php ./tk/bin/serve.php
```

# Application assets - old method
1. To install assets for example project, run in parent directory:

		php ./app/bin/samples/install-assets.php

	and follow the prompts  
	or you can use inline assets (but this is not recommended)

		export APP_INLINE_ASSETS=false

2. Compile assets to the public directory:

		php ./tk/bin/assets-compiler.php ./app/assets ./public/assets


# Materialized template
To enable the materialized template for components, set the environment variable:
```
export APP_MATERIALIZED=true
```

# Debug mode
**Note:** without this the automatic seeder will not start  
To switch the application to debug mode, set the environment variable:
```
export APP_ENV=dev
```
This variable will also enable the DebugBar and Symfony error handling if packages are installed.

# Password hash algorithm
To change the hash algorithm, set the environment variable:
```
export APP_PASSWD_HASH=argon2id
```
Available options: `bcrypt`, `argon2i`, `argon2id`, `plaintext`  
**warning:** `plaintext` can only be used in a dev env (`export APP_ENV=dev`)

# Session data in cookie
By default (if possible) the application stores session data in an encrypted cookie using the `sec_lv_encrypter.php` library.  
To switch the application to server-side session data storage mode, set the environment variable:
```
export APP_NO_SESSION_IN_COOKIE=true
```
For security reasons you can pass the key via an environment variable:
```
APP_SESSION_COOKIE_KEY=string-key
```
**Note:** the application will create a file for the key but will not save it there

# Application content
The application source code is located in the `app/src` directory:  
* controllers
* database connection configurations
* models
* routes
* views

### Routes
* `about.php` - `/about` (About toolkit) (has view)
* `bootstrap-test.php` - `/bootstrap-test` (Bootstrap framework test) (has view)
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
* `http_error.php` <-> `http-error-test.php`
* `check-date.php` <-> `check-date.php`
* `database-test.php` <-> `database-test.php`
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
The following connection configurations are for the `pdo_connect.php` library.
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
* `memcached` (for the `memcached_connect.php` library)
* `predis` (for the `predis_connect.php` library)
* `redis` (for the `redis_connect.php` library)

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

### DotEnv
Create `.env` file in parent directory:
```
APP_ENV=dev
APP_PASSWD_HASH=bcrypt
APP_INLINE_ASSETS=false
APP_MATERIALIZED=false
APP_NO_SESSION_IN_COOKIE=false
PGSQL_HOST=127.0.0.1
PGSQL_PORT=5432
PGSQL_DBNAME=sampledb
PGSQL_CHARSET=UTF8
PGSQL_USER=postgres
PGSQL_PASSWORD=postgres
MYSQL_HOST=[::1]
MYSQL_PORT=3306
MYSQL_DBNAME=sampledb
MYSQL_CHARSET=utf8mb4
MYSQL_USER=root
MYSQL_PASSWORD=root
DB_IGNORE_ENV=false
MEMCACHED_HOST=127.0.0.1
MEMCACHED_PORT=11211
MEMCACHED_IGNORE_ENV=false
REDIS_PREDIS=false
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_DBINDEX=0
REDIS_IGNORE_ENV=false
```
You can also use the following values:
```
APP_SESSION_COOKIE_KEY=string-key

PGSQL_SOCKET=/var/run/postgresql
MYSQL_SOCKET=/var/run/mysqld/mysqld.sock
SQLITE_PATH=path/to/database.sqlite3
MEMCACHED_SOCKET=/var/run/memcached/memcached.sock
REDIS_SOCKET=/var/run/redis/redis.sock
```

### Libraries
* `app_db_migrate.php` - `pdo_migrate.php` library integration with `pdo-connect.php` tool
* `app_params.php` - app input parameter manipulation library
* `app_root_path.php` - print path/URL to application root directory
* `app_session.php` - modular session backend
* `app_template.php` - default http headers and a `basic_template` overlay that saves typing
* `basic_template_config.php` - configuration interface
* `clickalicious_memcached.php` - Memcached polyfill - memcached.php proxy
* `maximebf_debugbar.php` - facade for Maxime Bouroumeau-Fuseau's DebugBar
* `ob_adapter.php` - modular output buffer
* `ob_cache.php` - a modular overlay for functions from the `ob_cache.php` library
* `pdo_instance.php` - get PDO handle
* `redis_instance.php` - combine `redis_connect.php` and `predis_connect.php` libraries
* `setup_login_library.php` - includes `sec_login.php` library and sets hashing algorithm
* `stdlib.php` - application standard library

### Sample libraries
* `app_db_migrate_log.php` - rubber cover for `app_db_migrate` that writes journals
* `app_setup_login_library.php` - `setup_login_library` configuration via environment variable
* `app_template_inline.php` - rubber overlay that implements `APP_INLINE_ASSETS` support for the `app_template.php` library
* `app_session.php` - implements `APP_NO_SESSION_IN_COOKIE` switch and `APP_SESSION_COOKIE_KEY` key for `app_session_mod_cookie`
* `logger.php` - logging functions
* `ob_adapter.php` - modular output buffer (additional modules)
* `ob_cache.php` - checks if it is possible to connect to Redis
* `pdo_instance.php` - get PDO handle (overlay)

### Components
* `basic_template` - simple template management
* `ie_error` - made without love

### Tools
* `app-down.php` - show board "road works"
* `mkmigration.php` - create new migration from template
* `replace-public-index-with-link.php`
* `run-php-tests.php`
* `session-clean.php` - remove stale sessions (if the application stores the session content in files)

### Sample tools
* `build-app.php` - sample application builder
* `install-assets.php`
* `remove-samples.php`
