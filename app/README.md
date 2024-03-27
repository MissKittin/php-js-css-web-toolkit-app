# Things to do after clone
1) `public/index.php` just imports another php file - this is stupid thing if your OS allows you to use softlinks.  
	You can remove this file and create link to `../app/entrypoint.php`.  
	Run in parent directory:  
	```
	php ./app/bin/replace-public-index-with-link.php
	```
2) to install assets for default template, run in parent directory:  
	```
	php ./app/bin/install-assets.php
	```
	and follow the prompts  
	or you can use inline assets (but this is not recommended)  
	```
	export APP_INLINE_ASSETS=yes
	```
3) compile assets to the public directory:  
	```
	php ./tk/bin/assets-compiler.php ./app/assets ./public/assets
	```

# Materialized template
To enable the materialized template for components, set the environment variable:  
```
export APP_MATERIALIZED=yes
```


# Removing samples
All example code is in `samples` dirs - ignore this fact.  
Remove samples and start developing application.  
To remove example application, run:  
```
php ./app/bin/remove-samples.php
```


# Application content

### Routes
* `about.php` - `/about` (About toolkit) (has view)
* `check-date.php` - `/check-date` (check_date() test) (has view)
* `database-test.php` - `/database-test` (Database libraries test) (has view)
* `home.php` - `/` (has view)
* `http-error-test.php` - `/http-error-test` (HTTP errors) and 404 page (has view)
* `login-component-test.php` - `/login-component-test` (Login component test) (has view)
* `login-library-test.php` - `/login-library-test` (Login library test) (has view)
* `obsfucate-html.php` - `/obsfucate-html` (HTML obsfucator test) (has view)
* `phar-test.php` - `/phar-test` (Toolkit in Phar test) (has view)
* `preprocessing-test.php` - `/preprocessing-test` (PHP preprocessing test) (has view)
* `robots.php` - `/robots.txt`
* `sitemap.php` - `/sitemap.xml`

### Controllers <-> routes
* `check-date.php` <-> `check-date.php`
* `database-test.php` <-> `database-test.php`
* `http_error.php` <-> `http-error-test.php`
* `login-component-test.php` <-> `login-component-test.php`
* `login-library-test.php` <-> `login-library-test.php`
* `phar-test.php` <-> `phar-test.php`
* `preprocessing-test.php` <-> `preprocessing-test.php`
* `robots-sitemap.php` <-> `robots.php` `sitemap.php`

### Models <-> routes
* `database_test_abstract.php` <-> `database-test.php`
* `login_component_test_credentials.php` <-> `login-component-test.php`
* `login_library_test_credentials.php` <-> `login-library-test.php`

### Databases (host:port db_name user password)
One of these databases is used in the `database_test_abstract.php` model.  
you can select a database via the `DB_TYPE` environment variable (default: `sqlite`).
* `pgsql` (127.0.0.1:5432 sampledb postgres postgres)
* `mysql` ([::1]:3306 sampledb root (no password))
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
* `DB_IGNORE_ENV=true` (ignores all the variables above and the DB_TYPE)

### Cache databases
You can configure the database connection through the following environment variables:
* `MEMCACHED_HOST` (default: 127.0.0.1)
* `MEMCACHED_PORT` (default: 11211)
* `MEMCACHED_SOCKET` (has priority over the host/port)
* `MEMCACHED_IGNORE_ENV=true` (ignores all memcached variables above)
* `REDIS_HOST` (default: 127.0.0.1)
* `REDIS_PORT` (default: 6379)
* `REDIS_SOCKET` (has priority over the host/port
* `REDIS_DBINDEX` (default: 0)
* `REDIS_IGNORE_ENV=true` (ignores all redis variables above)

### Libraries
* `app_template.php` - an overlay for the default template that saves typing
* `default_http_headers.php` - for (almost) all controllers
* `logger.php` - logging functions
* `ob_adapter.php` - `ob_start()` handler
* `ob_cache.php`
* `pdo_instance.php` - get PDO handler
* `session_start.php` - session handler

### Templates
* `default` - simple template management

### Tools
* `install-assets.php`
* `remove-samples.php`
* `replace-public-index-with-link.php`
* `session-clean.php` - remove stale sessions (if the application stores the session content in files)

### Predis
Predis is not officially supported, but you can test if it (not) works as ob_cache.  
You need composer - one of the methods of installing this invention is described in `HOWTO.md`  
Install Predis:
```
php ./tk/bin/composer.phar --optimize-autoloader --no-cache require predis/predis
```
then set the environment variable:
```
export REDIS_PREDIS=true
```
All `REDIS_*` variables are respected
