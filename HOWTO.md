* [Toolkit as a composer package](#toolkit-as-a-composer-package)
* [A few words about design](#a-few-words-about-design)
	* [Application libraries](#application-libraries)
	* [Application standard library](#application-standard-library)
	* [Removing unused toolkit libraries](#removing-unused-toolkit-libraries)
	* [Upload tmp directory](#upload-tmp-directory)
* [Upgrading](#upgrading)
* [Creating The project](#creating-the-project)
	* [Toolkit libraries used by extra tools](#toolkit-libraries-used-by-extra-tools)
	* [GNU GPL](#gnu-gpl)
	* [Hint - git](#hint---git)
	* [Hint - index.php](#hint---indexphp)
	* [Testing toolkit](#testing-toolkit)
	* [Example application](#example-application)
	* [Removing example application](#removing-example-application)
	* [Removing documentation](#removing-documentation)
	* [PHP polyfill Component Cache](#php-polyfill-component-cache)
	* [JavaScript polyfills](#javascript-polyfills)
	* [Packing into Phar](#packing-into-phar)
		* [The toolkit](#the-toolkit)
		* [The application](#the-application)
		* [The Composer](#the-composer)
	* [Installing Composer](#installing-composer)
	* [`mbstring` and `iconv` polyfills](#mbstring-and-iconv-polyfills)
	* [Installing Predis](#installing-predis)
	* [Memcached without PECL](#memcached-without-pecl)
	* [PSR-3 (logger interface)](#psr-3-logger-interface)
	* [PSR-11 (dependency injection containers)](#psr-11-dependency-injection-containers)
* [How to create application](#how-to-create-application)
	* [.env](#env)
	* [Debug Bar](#debug-bar)
	* [Symfony error handler](#symfony-error-handler)
	* [Templates](#templates)
	* [Tests](#tests)
	* [Autoloading (toolkit and application)](#autoloading-toolkit-and-application)
	* [PSR-4 autoloading (application only)](#psr-4-autoloading-application-only)
	* [Configuring URL routing](#configuring-url-routing)
	* [Creating assets](#creating-assets)
	* [Sass von der Less](#sass-von-der-less)
	* [Creating database configuration for pdo_connect()](#creating-database-configuration-for-pdo_connect)
	* [Queue worker](#queue-worker)
	* [WebSockets](#websockets)
	* [Compiling assets](#compiling-assets)
	* [Minifying assets - webdev.sh client](#minifying-assets---webdevsh-client)
	* [Minifying assets - matthiasmullie's minifier](#minifying-assets---matthiasmullies-minifier)
	* [Single Page Application](#single-page-application)
	* [Seeding database offline with pdo_connect()](#seeding-database-offline-with-pdo_connect-optional)
	* [Running dev server](#running-dev-server)
	* [Maintenance break](#maintenance-break)
	* [Preloading application](#preloading-application)
	* [Task scheduling](#task-scheduling)
	* [Rotating logs](#rotating-logs)
	* [Removing symlinks](#removing-symlinks)
	* [Deploy on shared hosting in a subdirectory](#deploy-on-shared-hosting-in-a-subdirectory)
* [Apache configuration](#apache-configuration)
* [nginx configuration](#nginx-configuration)

# Toolkit as a composer package
It is possible to install the toolkit as a composer package. The toolkit itself is not integrated with it - the `misskittin/php-js-css-web-toolkit-pkg` plugin is used for this purpose.  
**Note:** the necessary packages are not available in packagist.org - composer repositories are in the `repo/` branches.  
You can find more information on this topic in the `php-js-css-web-toolkit-pkg` git repository.

# A few words about design
The application design does not define the paradigm in which individual parts of the application will be written.  
You decide what will be written procedurally, functionally and object-oriented, as well as what type of routing you will use.  
It has its advantages and disadvantages - it is not a typical framework, so think about whether you need something like Symfony or Laravel.  
You can edit everything except the toolkit files (`tk`) and its extras (`tke`) - this gives you the ability to update the toolkit by updating the git submodule.  
**Remember about safety!**  
**Treat all data coming from the user as untrusted. Filter them before sending them back.**  
**Keep your code clean.**

### Application libraries
All files in the `app/lib` directory are facades for toolkit libraries and components.  
You can edit them, you can extend them - you decide how to design your application.  
They implement additional features such as operation cache, environment variables, modularization of code over regular functions etc  
The core algorithms remain in the toolkit repositories.  
All except

### Application standard library
The `app/lib/stdlib.php` library is the parent library of the application - it is loaded by the `app/entrypoint.php`.  
All application elements except tools (components, libraries, controllers, routes etc.) assume that the library is already loaded.  
Provides constants with paths to specific parts of the application:
* `APP_STDLIB` - it's always `1`
* `APP_STDLIB_CACHE` - `1` if constants were imported from cache
* `APP_ROOT` - path to the application's root directory
* `APP_DIR` - path to `app` directory (`APP_ROOT/app`)
* `APP_COM` - path to `app/com` directory
* `APP_LIB` - path to `app/lib` directory
* `APP_SRC` - path to `app/src` directory
* `APP_CTRL` - path to `app/src/controllers` directory
* `APP_DB` - path to `app/src/databases` directory
* `APP_MODEL` - path to `app/src/models` directory
* `APP_ROUTE` - path to `app/src/routes` directory
* `APP_VIEW` - path to `app/src/views` directory
* `TK_PHAR` - path to `tk.phar` file (`APP_ROOT/tk.phar`)
* `TK_COM` - path to `tk/com` or `tk.phar/com` directory
* `TK_LIB` - path to `tk/lib` or `tk.phar/lib` directory
* `TKE_PHAR` - path to `tke.phar` file (`APP_ROOT/tke.phar`)
* `TKE_COM` - path to `tke/com` or `tke.phar/com` directory
* `TKE_LIB` - path to `tke/lib` or `tke.phar/lib` directory
* `VAR_DIR` - path to `var` directory (`APP_ROOT/var`)
* `VAR_CACHE` - path to `var/cache` directory
* `VAR_LIB` - path to `var/lib` directory
* `VAR_DB` - path to `var/lib/databases` directory
* `VAR_SESS` - var/lib/sessions` directory
* `VAR_LOG` - path to `var/log` directory
* `VAR_RUN` - path to `var/run` directory
* `VAR_TMP` - path to `var/tmp` directory

If you pack the toolkit into a phar, the library will set the paths so that files are loaded from the archive.  
Also, if you don't use git, you can install the toolkit as a composer package.  
**Note:** in this situation you can use both autoloader and `load_function('function_name');` or without autoloading (`require TK_LIB.'/library.php';`). For more info see `php-js-css-web-toolkit-pkg` repository.  
The library also includes an application bootstrap that creates a `var` directory hierarchy.  
When you first run the application or change the location of the application directory, the library creates a cache file (`var/cache/stdlib_cache.php`).  
**Warning:** if you want to change the location of the toolkit to local (`com`, `lib`), phar (`tk.phar`, `tke.phar`) or composer (`vendor`), you need to remove this file.  
Additionally, it provides:
* `app_exception` class

		throw new app_exception('Fehler!');

* `app_env` function  
	that uses `dotenv.php` library  
	see [app README DotEnv](app/README.md#dotenv)

		$env_var=app_env('ENV_VAR_NAME');
		$env_var=app_env('ENV_VAR_NAME', 'default_value');

* `app_ioc` function  
	which provides `ioc_autowired_container`  
	for more info see `ioc_container.php` library

		app_ioc()
		->	set('my_class', function($container){
				return new my_class();
			});
		$my_class_instance=app_ioc('my_class');


### Removing unused toolkit libraries
The standard library will set the `TK_COM`, `TK_LIB`, `TKE_COM` and `TKE_LIB` constants to the `com` and `lib` directories in the project root if it finds them.  
This allows you to remove unused components and libraries from the project once your application is complete.  
It's up to you how you track and copy them, as well as automate copying and updating.  
**Warning:** remember that tools and components have dependencies!  
**Hint:** after copying libraries and components, you can simply deinit git submodules:
```
git submodule deinit tk
git submodule deinit tke
```

### Upload tmp directory
You can set this path to the application temporary directory:
```
upload_tmp_dir = /absolute/path/to/php-js-css-web-toolkit-app/var/tmp
```
You have to do it manually because PHP allows changing this value only in the system `php.ini` file

# Upgrading
First, review the changes documented in the toolkit's `CHANGELOG.md` (`tk/CHANGELOG.md`) (if you use extras, read their changelog too).  
Each component has its own changelog - read the ones you use.  
Then update git submodules:
```
git -C tk pull origin stable
git -C tke pull origin stable
```
and modernize the application relying on changelogs and documentation.  
At the very end you can update the application tools (`app/bin`), components (`app/com`) and libraries (`app/lib`) - the changes are described in the `CHANGELOG.md` file in this directory.  
**Note:** the method of upgrading application tools, components and libraries is up to you  
**Note:** application components also have their own changelogs

# Creating The project
**Hint:** to automate this process, use the `create-php-toolkit-app.php` tool from the `php-js-css-web-toolkit-extras` repository
```
git clone --recursive --depth 1 --shallow-submodules -b stable "https://github.com/MissKittin/php-js-css-web-toolkit-app.git" my-awesome-project
cd my-awesome-project
php ./app/bin/git-init.php
```
To add unofficial extras, run:
```
git submodule add --depth 1 -b stable "https://github.com/MissKittin/php-js-css-web-toolkit-extras.git" tke
```

### Toolkit libraries used by extra tools
Some extra tools require toolkit libraries and they don't know where they are.  
Tell them this via environment variables:
```
# nix
export TK_COM=./tk/com
export TK_LIB=./tk/lib
```
```
rem windows
set TK_COM=.\tk\com
set TK_LIB=.\tk\lib
```

### GNU GPL
It happens that GPL license is not wanted - if you link to a GPL-licensed library, your project must also be GPL-licensed.  
If you are in such a situation, you can remove them:
```
php ./tke/bin/remove-gpl.php --yes
```

### Hint - git
If you won't need git anymore, you can delete the git repo data: run  
for *nix:
```
rm -rf .git; rm .gitmodules; rm tk/.git; rm -rf tke/.git
```
for windows:
```
rd /s /q .git && del .gitmodules && del tk\.git && rd /s /q tke\.git
```
but if you want to have a smaller repository and the ability to update the toolkit, change the submodule URL:
```
git submodule set-url tk "https://github.com/MissKittin/php-js-css-web-toolkit.git"
git commit -a -m "Changed URL of tk submodule"
```
and then change the remote repository address and optionally push:
```
git remote set-url origin "git@github.com:YourName/my-app.git"
git push origin master
```
remember that after updating the `tk` submodule all changes to the `tk` directory will be lost!

### Hint - index.php
`public/index.php` just imports another php file - this is stupid thing if your OS allows you to use softlinks.  
You can remove this file and create link to `../app/entrypoint.php`:
```
php ./app/bin/replace-public-index-with-link.php
```

### Testing toolkit
Before testing you can export environment variables:
```
export TEST_DB_TYPE=pgsql # pgsql, mysql, sqlite; default: sqlite

export TEST_PGSQL_HOST=127.0.0.1 # default
export TEST_PGSQL_PORT=5432 # default
export TEST_PGSQL_SOCKET=/var/run/postgresql # has priority over the HOST
export TEST_PGSQL_DBNAME=php_toolkit_tests # default
export TEST_PGSQL_USER=postgres # default
export TEST_PGSQL_PASSWORD=postgres # default

export TEST_MYSQL_HOST=[::1] # default
export TEST_MYSQL_PORT=3306 # default
export TEST_MYSQL_SOCKET=/var/run/mysqld/mysqld.sock # has priority over the HOST
export TEST_MYSQL_DBNAME=php_toolkit_tests # default
export TEST_MYSQL_USER=root # default
export TEST_MYSQL_PASSWORD=mypassword # not set by default

export TEST_REDIS=yes # default: no
export TEST_REDIS_PREDIS=yes # connect to redis via predis_connect.php library
export TEST_REDIS_HOST=127.0.0.1 # default
export TEST_REDIS_SOCKET=/var/run/redis/redis.sock # has priority over the HOST
export TEST_REDIS_PORT=6379 # default
export TEST_REDIS_DBINDEX=0 # default
export TEST_REDIS_USER=myuser # not set by default
export TEST_REDIS_PASSWORD=mypass # not set by default

export TEST_MEMCACHED=yes # default: no
export TEST_MEMCACHED_CM=yes # use polyfill from clickalicious_memcached.php library
export TEST_MEMCACHED_HOST=127.0.0.1 # default
export TEST_MEMCACHED_SOCKET=/var/run/memcached/memcached.sock # has priority over the HOST
export TEST_MEMCACHED_PORT=11211 # default

export TEST_APCU=yes # default: no
```
To check if all libraries and components will work on your server, run:
```
php ./tk/bin/run-php-bin-tests.php
php ./tk/bin/run-php-lib-tests.php
php ./tk/bin/run-php-com-tests.php
```
You can also run the HTTP server to test the CSS and Js libraries on the web browser:
```
php ./tk/bin/run-phtml-tests.php
```
You can also provide the path to the directory with tests for the above tools, eg:
```
php ./tk/bin/run-php-bin-tests.php ./tke/bin/tests
php ./tk/bin/run-php-lib-tests.php ./tke/lib/tests
php ./tk/bin/run-php-com-tests.php ./tke/com
php ./tk/bin/run-phtml-tests.php ./tke/lib/tests
```
If a test failed and you care about specific tools/components/libraries, run the test(s) manually

### Example application
Instructions on how to run the sample application are in [app README](app/README.md)  
After configuring the application, [start the HTTP server](#running-dev-server)  
If you want to create your own app, read on.

### Removing example application
All example application code is in `samples` dirs.  
Remove samples and start developing application: run:
```
php ./app/bin/samples/remove-samples.php
```

### Removing documentation
All library documentation is contained within the libraries themselves.  
It is not needed in the production or in the Phar.  
You can reduce the size of php files:
```
php ./tk/bin/strip-php-files.php ./tk
php ./tk/bin/strip-php-files.php ./tke
```
or you can also remove all tests and markdown files:
```
php ./tk/bin/strip-php-files.php ./tk --remove-tests --remove-md
php ./tk/bin/strip-php-files.php ./tke --remove-tests --remove-md
```
**Warning:** if you update the toolkit, you need to repeat this procedure

### PHP polyfill Component Cache
This component includes `pf_*.php` libraries depending on PHP version.  
But what if there was one file instead of many?  
Method 1: if you do not intend to use the submodule (e.g. pack it into phar or integrate it with the application repository):
```
php ./tk/com/php_polyfill/bin/mkcache.php
php ./tk/com/php_polyfill/bin/destroy.php
```
Method 2: if you don't want to change anything in the submodule (smaller application repository, ability to update the submodule):
```
php ./tk/com/php_polyfill/bin/mkcache.php --out ./app/php_polyfill.php
```
I gra gitara :)

### JavaScript polyfills
The Toolkit does not support ECMAScript older than 6.  
If you need compatibility with very old browsers, you must take care of it yourself.

### Packing into Phar
You can bundle individual parts of a project.  
This will save disk space and make it much easier to transfer them.  
However, this will negatively impact performance - **use it wisely**.

#### The toolkit
If a better option is to pack the libraries and components into one file, use the `mkphar.php` utility:
```
cd ./tk
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=com --source=lib --ignore=assets/ --ignore=bin/ --ignore=tests/ --ignore=tmp/ --ignore=README.md --output=../tk.phar
cd ../tke
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=lib --ignore=tests/ --ignore=tmp/ --ignore=README.md --output=../tke.phar
```
and use the generated Phar, eg:
```
<?php
	require 'phar://'
	.	'./tk.phar'
	.	'/com/admin_panel/admin_panel.php';
?>
```
If you don't need the css and js files, you can ignore them:
```
cd ./tk
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=com --source=lib --ignore=assets/ --ignore=bin/ --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css --output=../tk.phar
cd ../tke
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=lib --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css --output=../tke.phar
```
and if you only need to include one file(s), use the `--include` option:
```
cd ./tk
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=com --source=lib --ignore=assets/ --ignore=bin/ --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css --include=/sleep.js --output=../tk.phar
cd ../tke
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=lib --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css --include=/melinskrypt.js --output=../tke.phar
```
or `--include-regex` option:
```
cd ./tk
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=com --source=lib --ignore=assets/ --ignore=bin/ --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css "--include-regex=sleep.js$" --output=../tk.phar
cd ../tke
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=lib --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css "--include-regex=melinskrypt.js$" --output=../tke.phar
```
if you used the first method of cache php polyfill, you can ignore the `pf_*.php` libraries:
```
cd ./tk
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=com --source=lib --ignore=assets/ --ignore=bin/ --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css "--ignore-regex=\/pf_(.*?).php" --output=../tk.phar
cd ../tke
php -d phar.readonly=0 ./bin/mkphar.php --compress=gz --source=lib --ignore=tests/ --ignore=tmp/ --ignore=README.md --ignore=.js --ignore=.css --output=../tke.phar
```
you can also use `--include-regex` option.

#### The application
First you need to edit the `app/lib/stdlib.php` library.  
There is a commented out block of code that sets the `APP_ROOT` constant. Follow the instructions in the comment above that block.  
Then run in the project's root directory:
```
php -d phar.readonly=0 ./tk/bin/mkphar.php --compress=gz --source=app --ignore=bin/ --ignore=tests/ --ignore=README.md --output=app.phar
```
You can add the `--ignore=assets/` if you are not using inline assets.  
To improve performance you can disable compression by removing the `--compress` argument.  
The last step is to edit the `public/index.php`:
```
<?php require 'phar://../app.phar/app/entrypoint.php'; ?>
```
The `app/lib/stdlib.php` library will recognize if the application is bundled, you don't have to worry about it.

#### The Composer
Run in the project's root directory:
```
php -d phar.readonly=0 ./tk/bin/mkphar.php --compress=gz --source=vendor --ignore=bin/ --ignore=build/ --ignore=doc/ --ignore=docs/ --ignore=Test/ --ignore=Tests/ --ignore=tests/ --ignore=.codeclimate --ignore=.doctrine-project.json --ignore=.github/ --ignore=.codeclimate.yml --ignore=.editorconfig --ignore=.gitattributes --ignore=.gitignore --ignore=.gitmodules --ignore=build.properties --ignore=build.xml --ignore=CHANGELOG --ignore=CHANGELOG.md --ignore=composer.json --ignore=composer.lock --ignore=CONTRIBUTING.md --ignore=extension.neon --ignore=Makefile --ignore=psalm.xml --ignore=renovate.json --ignore=.php_cs --ignore=.phpstorm.meta.php --ignore=.rmt.yml --ignore=.scrutinizer.yml --ignore=.sensiolabs.yml --ignore=.styleci.yml --ignore=.travis.yml --ignore=.whitesource "--ignore-regex=.dist$"  "--ignore-regex=.sh$" "--ignore-regex=(?i)README.md$" --output=vendor.phar
```
To improve performance you can disable compression by removing the `--compress` argument.  
`app/entrypoint.php` is already prepared for this, you don't need to edit anything.

### Installing Composer
To install Composer, run:
```
php ./tk/bin/get-composer.php
```
Composer will be installed in `./tk/bin/composer.phar`

### `mbstring` and `iconv` polyfills
Due to the fact that some libraries require the `mbstring` extension which is a non-default, you can install the `polyfill-mbstring` package:
```
php ./tk/bin/composer.phar require symfony/polyfill-mbstring
```
The above package uses the `iconv` extension.  
If you don't have the ability to enable this extension, you can install the `polyfill-iconv` package:
```
php ./tk/bin/composer.phar require symfony/polyfill-iconv
```
**Note:** both packages do not conflict with the `php-polyfill` component

### Installing Predis
Predis is supported but not directly - the PHPRedis API (`redis` PHP extension) is used by default.  
For Predis, a `predis_phpredis_proxy` class from the `predis_connect.php` library is needed.  
To install Predis, run:
```
php ./tk/bin/composer.phar require predis/predis
```
For more information, see the `predis_connect.php` library  
**Note:** Predis requires PHP >= 7.2, for PHP >= 5.3.9 use Predis v1.1.10  
**Hint:** it is possible to install Predis without Composer using Git (not recommended):
```
php -r "file_put_contents('./composer.json', '');"
git clone "https://github.com/predis/predis.git" ./vendor
```

### Memcached without PECL
You can use Memcached without the PECL extension via the memcached.php package.  
To install memcached.php, run:
```
php ./tk/bin/composer.phar require clickalicious/memcached.php
```
Then include the `clickalicious_memcached.php` library:
```
if(
	(!class_exists('Memcached'))
	class_exists('\Clickalicious\Memcached\Client')
)
	require TK_LIB.'/clickalicious_memcached.php';
```
This library works with the `memcached_connect.php` and other libraries.

### PSR-3 (logger interface)
The implementation is relatively simple, an example adapter is provided in the library documentation.  
For more info, see `logger.php` library and visit [PHP-FIG](https://www.php-fig.org/psr/psr-3/) website.

### PSR-11 (dependency injection containers)
Implementation of interfaces is possible by inheriting a selected class from the `ioc_container.php` library.  
However, due to package changes (type hints) I could not adapt this library to support so many PHP versions and at the same time implement the `ContainerInterface`.  
Therefore, you need to write the adapter yourself and optionally edit the `app_ioc` function in the `app/lib/stdlib.php` library.  
For more info, see `ioc_container.php` library and visit [PHP-FIG](https://www.php-fig.org/psr/psr-11/) website.

# How to create application

### .env
Create an `.env` file in the current directory.  
The following options are available to start with:
```
APP_ENV=dev
APP_PASSWD_HASH=bcrypt
PGSQL_HOST=127.0.0.1
PGSQL_PORT=5432
PGSQL_DBNAME=mydatabasename
PGSQL_CHARSET=UTF8
PGSQL_USER=postgres
PGSQL_PASSWORD=postgres
MYSQL_HOST=[::1]
MYSQL_PORT=3306
MYSQL_DBNAME=mydatabasename
MYSQL_CHARSET=utf8mb4
MYSQL_USER=root
MYSQL_PASSWORD=root
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_DBINDEX=0
MEMCACHED_HOST=127.0.0.1
MEMCACHED_PORT=11211
```
where `APP_ENV` can be `dev` (enables developer tools) or whatever  
and `APP_PASSWD_HASH` (`setup_login_library.php` library) can be `bcrypt` (default), `argon2i`, `argon2id` or `plaintext` (for debugging purposes only).  

Additionally, the following options are available:
```
APP_SESSION_COOKIE_KEY=string-key
PGSQL_SOCKET=/var/run/postgresql
MYSQL_SOCKET=/var/run/mysqld/mysqld.sock
SQLITE_PATH=path/to/database.sqlite3
REDIS_SOCKET=/var/run/redis/redis.sock
MEMCACHED_SOCKET=/var/run/memcached/memcached.sock
```

### Debug Bar
A useful function that allows you to have insight into what is happening inside the application.  
The `tk/lib/maximebf_debugbar.php` and `app/lib/maximebf_debugbar.php` libraries provides support for the package and if it is not active it uses a dummy class - there is no need to remove the debugging code.  
You just need to install the package:
```
php ./tk/bin/composer.phar require --dev php-debugbar/php-debugbar
```
For PHP 7.1.0 use version 1.18.1:
```
php ./tk/bin/composer.phar require --dev php-debugbar/php-debugbar:1.18.1
```
DebugBar will only be activated if the `APP_ENV` variable is set appropriately:
```
export APP_ENV=dev
```
**Warning:** the library requires a package version 1.17.0 or newer  
For more info see `tk/lib/maximebf_debugbar.php` and `app/lib/maximebf_debugbar.php` libraries, [the package's test page](http://phpdebugbar.com/) and its [documentation](http://phpdebugbar.com/docs/).

### Symfony error handler
To make exceptions more readable, you can install the error handler from Symfony.  
The code that starts the handler is already in `app/entrypoint.php`, you just need to install the package:
```
php ./tk/bin/composer.phar require --dev symfony/error-handler
```
If you are using older versions of PHP, you can use the abandoned package:
```
php ./tk/bin/composer.phar require --dev symfony/debug
```
Error handler will only be activated if the `APP_ENV` variable is set appropriately:
```
export APP_ENV=dev
```

### Templates
Copy them and start creating  
**Hint:** you can edit controller and model templates and inherit from them - this way you will keep the DRY rule  
**Note:** you can define in the models which database you will use or you can use the default database (`pdo_instance::set_default_db()`)

* `app/src/controllers/controller_template.php`
* `app/src/databases` - default configurations for `pdo connect.php` library
* `app/src/models/cache_model_template.php`
* `app/src/models/json_model_template.php`
* `app/src/models/pdo_model_template.php`
* `app/src/models/session_model_template.php`
* `app/src/routes/route_template.php`
* `app/src/views/view_template`  
	`template_config_ng.php` file contains configuration template via `app/lib/basic_template_config.php` library  
	to apply, rename `template_config_ng.php` to `template_config.php`
* `app/tests/ns_test_template.php` - allows you to create mock PHP functions and classes
* `app/tests/test_template.php`

### Tests
`app/tests` has the same layout as `app/src` - tests are divided into individual sections.  
Copy the test template to a similar location as in `app/src` and rename the file to the same name as the file being tested.  
If you need a mock built-in function or class, use the `ns_test_template.php` template.  
Then edit `$stdlib_path`, add required libraries and put testing code in `// test body` section.  
To run all tests at once use the `app/bin/run-php-tests.php` tool.

### Autoloading (toolkit and application)
You can generate an autoloader script for toolkit libraries (functions and classes), application, or both using the `autoloader-generator.php` tool.  
For more info, run:
```
php ./tk/bin/autoloader-generator.php
```
**Warning:** don't forget to include the generated file in the `app/entrypoint.php`

### PSR-4 autoloading (application only)
The routing, controller and model templates have a namespace (`app/src`) defined in their header.  
Just uncomment them along with the necessary `use` directives and add the namespace to `composer.json`:
```
{
    "autoload": {
        "psr-4": {
            "app\\": "app/"
        }
    }
}
```
Then run:
```
php ./tk/bin/composer.phar dump-autoload
```
In the `routing_template.php` there is a `require` directive for the controller and model:
```
require APP_CTRL.'/controller_template.php';
require APP_MODEL.'/pdo_model_template.php';
require APP_MODEL.'/cache_model_template.php';
```
you don't need this anymore - Composer will take care of it from now on.  
**Note:** `routing_template.php` is procedural, but there is no objection to converting it to OOP.

### Configuring URL routing
Edit `app/entrypoint.php`  
You can use eg. `uri_router.php` library or `superclosure_router` component.

### Creating assets
See `assets_compiler.php` library.

### Sass von der Less
First see `assets_compiler.php` library.  
You can use any tool you want, in this example we will use node.js:
```
npm install sass
npm install less
```
Use the preprocessed asset: in `main.php`
```
<?php
	echo shell_exec('node ./node_modules/.bin/sass "'.__DIR__.'/main.scss"');
	echo shell_exec('node ./node_modules/.bin/lessc "'.__DIR__.'/main.less"');
?>
```

But if you don't любишь java in "script" version (like me), you can do it in PHP too:
```
php ./tk/bin/composer.phar require --dev scssphp/scssphp
php ./tk/bin/composer.phar require --dev leafo/lessphp
```
Use the preprocessed asset: in `main.php`
```
<?php
	echo shell_exec('"PHP_BINARY" ./vendor/bin/pscss "'.__DIR__.'/main.scss"');
	echo shell_exec('"PHP_BINARY" ./vendor/bin/plessc "'.__DIR__.'/main.less"');

	// lessphp has a bug in earlier versions. if you don't have an vendor/bin/plessc file, use this:
	//echo shell_exec('"PHP_BINARY" ./vendor/leafo/lessphp/plessc "'.__DIR__.'/main.less"');
?>
```

### Creating database configuration for pdo_connect()
See `pdo_connect.php` library.

### Queue worker
It is better to do more time-consuming tasks outside the main application.  
The `queue_worker.php` library and the `queue-worker.php` tool are available for this purpose.  
This library has several methods for transporting data. You can choose.  
For more info, see `./tk/lib/queue_worker.php`and `./tk/bin/queue_worker.php` and run:
```
php ./tk/bin/queue-worker.php
```

### WebSockets
**Note:** this tool uses the features of *nix systems and is only intended for them.  
For more info, run:
```
php ./tk/bin/websockets.php
```
*Warning: Chrome doesn't allow unsecure websocket (ws) connections, so it may not work on HTTP!*

### Compiling assets
```
php ./tk/bin/assets-compiler.php ./app/assets ./public/assets
```
or you can watch for changes:
```
php ./tk/bin/file-watch.php "php ./tk/bin/assets-compiler.php ./app/assets ./public/assets" ./app/assets
```
or you can watch for changes and new files:
```
php ./tk/bin/file-watch.php "php ./tk/bin/assets-compiler.php ./app/assets ./public/assets" ./app/assets --extended
```
For more info, run:
```
php ./tk/bin/assets-compiler.php 
php ./tk/bin/file-watch.php
```
**Hint:** preprocessed assets with `.php` extension can be semi-processed assets - use `readfile(__DIR__.'/filename.php');` in `main.php` :)

### Minifying assets - webdev.sh client
**Note:** before using it you need to add the extras submodule, see [here](#creating-the-project)
```
php ./tke/bin/webdev.sh --dir ./public/assets
```
All css and js files in `public/assets` will be minified.

### Minifying assets - matthiasmullie's minifier
The way of using `./tk/bin/matthiasmullie-minify.php` is the same as in the webdev.sh client.  
**Note:** before use, you need to install the composer and minifier package:
```
mkdir ./tk/bin/composer
php ./tk/bin/composer.phar --working-dir=./tk/bin/composer require matthiasmullie/minify
```
if the toolkit project is a composer package, you just need to install the package:
```
composer require --dev matthiasmullie/minify
```
and run the tool:
```
composer tk matthiasmullie-minify --dir ./public/assets
```

### Single Page Application
I won't give you a ready-made method, you have to design it yourself.  
You have to decide whether the frontend will be a separate project or maybe in the application repository.  
I can give you a hint: the magic is all in the bundler configuration.  
For example, for VITE you need to add to the `vite.config.js`:
```
import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'

//

export default defineConfig({
  //

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./app/ui', import.meta.url)) // instead of ./src
    }
  },
  root: './app/ui', // instead of ./src
  build: {
    outDir: '../../public', // instead of ../dist
    emptyOutDir: false // do not purge public dir
  }
})
```
For convenience, add scripts to `package.json`:
```
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview"
  }
}
```
To add the frontend source code to your application, follow these steps:
* install npm packages
* configure bundler
* create `app/ui` directory - this will be your `src` directory
* create file `app/ui/index.html` - this will be the entrypoint of the application frontend
* create a directory `public/api` and move files `public/index.php` and `public/.htaccess` to it
* correct `../app/entrypoint.php` to `../../app/entrypoint.php` in `public/api/index.php`
* add to the `app/entrypoint.php` to disable CORS restrictions during development:

		if(app_env('APP_ENV') === 'dev')
		{
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Credentials: true');
		}

* configure the frontend API support so that for the development environment the URL points to `http://localhost:8080/api`, but for the production environment it points to `/api`
* `npm run dev`  
	run also `APP_ENV=dev php ./tk/bin/serve.php` in background to access API, kill when done
* `npm run build`
* `php ./tk/bin/serve.php`  
	from now on, the frontend is served by a built-in PHP server

### Seeding database offline with pdo_connect() (optional)
To offline seed database, run:
```
php ./tk/bin/pdo-connect.php --db ./app/src/databases/database-name
```
If you need a more advanced system, you can use the `pdo_migrate.php` library.  
**Note:** database can be seeded automatically on first start (see [app README Debug mode](app/README.md#debug-mode)).

### Running dev server
In this directory run:
```
php ./tk/bin/serve.php
```
You can also specify IP, port, preload script, document root and server threads, eg.
```
php ./tk/bin/serve.php --ip 127.0.0.1 --port 8080 --docroot ./public --preload ../var/lib/app-preload.php --threads 4
```

### Maintenance break
Disable application:
```
php ./app/bin/app-down.php down
```
Enable application:
```
php ./app/bin/app-down.php up
```
Get application status:
```
php ./app/bin/app-down.php status && echo "App up" || echo "App down"
```

### Preloading application
You have to write the preloader yourself:
* if the script only defines a constants, functions, classes, traits or interfaces, use `require`
* in other cases use `opcache_compile_file`

and set the `opcache.preload` value in `php.ini`:
```
opcache.preload=/absolute/path/to/preload.php
```

If your application does not use autoloader, constants or variables in `include`/`require` directives,  
you can use `opcache-preload-generator.php` tool from [php-js-css-web-toolkit-extras](#creating-the-project) repository.  
For more info, run:
```
php ./tke/bin/opcache-preload-generator.php
```

### Task scheduling
For info, run:
```
php ./tk/bin/cron.php
```

### Rotating logs
Text files can swell to large sizes - this can degrade application performance.  
To prevent this from happening, you can use the `logrotate.php` utility.  
For more info, run:
```
php ./tk/bin/logrotate.php
```

### Removing symlinks
In some cases, you may need to replace symbolic links with the target files.  
This process can be automated using the `link2file.php` tool:
```
php ./tk/bin/link2file.php ./app
```

### Deploy on shared hosting in a subdirectory
**Note:** the application itself finds the path to the assets (`public/assets`) directory (`app/lib/app_template.php` library)  
    you don't have to worry about it  
**Note:** the application itself removes subdirectories from the parameter path using the `app/lib/app_params.php` library  
    there is no need to edit the switch argument in the `app/entrypoint.php` file

* hosting with `public_html`
	1. mkdir `./public_html` and `./your-app`
	2. move `./public` to `./public_html` and rename to `app-name`
	3. move everything to `./your-app` except `./public_html`
	4. edit `./public_html/app-name/index.php` and correct the require path  
		(here: `require '../../your-app/app/entrypoint.php';`)
	5. test the application:

			php ./your-app/tk/bin/serve.php --docroot ./public_html

		and open a web browser: `http://127.0.0.1:8080/app-name/`
	6. `bin` and `tests` directories may no longer be needed - you can delete any `bin` and `tests` directories you find
	7. upload `./public_html/app-name` directory to `public_html/app-name`  
		(where the second `public_html` is document root in your hosting)
	8. upload `./your-app` to `your-app`  
		(next to the `public_html` directory in your hosting)

* hosting without `public_html`
	1. mkdir `./my-app` and `./my-app/app-src`
	2. move everything to `./my-app/app-src` except `./public`
	3. move `./public/*` and `./public/.htaccess` to `./my-app` and rmdir `./public`
	4. edit `./my-app/index.php` and correct the require path  
		(here: `require './app-src/app/entrypoint.php';`)
	5. create `./my-app/app-src/.htaccess` file:

			RewriteEngine on
			RewriteRule . ../index.php [L]

	6. test the application:

			php ./my-app/app-src/tk/bin/serve.php --docroot .

		and open a web browser: `http://127.0.0.1:8080/app-name/`
	7. `bin` and `tests` directories may no longer be needed - you can delete any `bin` and `tests` directories you find
	8. upload project to `my-app`


# Apache configuration
You need to `a2enmod rewrite`  
Optionally you can also `a2enmod headers`  

HTTP only:
```
<VirtualHost *:80>
	ServerName myapp.com
	ServerAlias www.myapp.com

	HostnameLookups off

	DocumentRoot /absolute/path/to/php-js-css-web-toolkit-app/public

	<Directory "/absolute/path/to/php-js-css-web-toolkit-app/public">
		Options Indexes FollowSymlinks
		AllowOverride All
		Require all granted
	</Directory>

	# Use PHP-FPM (you need to a2enmod proxy_fcgi)
	#<FilesMatch \.php$>
	#	SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost"
	#</FilesMatch>

	# Proxy to the websockets.php server (you need to a2enmod proxy_wstunnel)
	#RewriteEngine on
	#RewriteCond %{REQUEST_URI} !/ws$
	#RewriteCond %{HTTP:Upgrade} websocket [NC]
	#RewriteCond %{HTTP:Connection} Upgrade [NC]
	#RewriteRule .* ws://localhost:8081%{REQUEST_URI} [P,L]

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

HTTPS only:
```
<VirtualHost *:80>
	RewriteEngine on
	RewriteCond %{HTTPS} !=on
	RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R=301,L]
</VirtualHost>
<VirtualHost *:443>
	ServerName myapp.com
	ServerAlias www.myapp.com

	HostnameLookups off

	# You need to a2enmod ssl
	SSLEngine on
	SSLCertificateFile /etc/apache2/certificates/myapp.com.crt
	SSLCertificateKeyFile /etc/apache2/certificates/myapp.com.key

	# You need to a2enmod socache_shmcb
	SSLSessionCache shmcb:/run/httpd/sslcache(1024000)
	SSLSessionCacheTimeout 600
	KeepAlive On
	MaxKeepAliveRequests 50
	KeepAliveTimeout 70
	SSLProtocol TLSv1 TLSv1.1 TLSv1.2
	SSLCipherSuite HIGH:!aNULL:!MD5
	Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

	DocumentRoot /absolute/path/to/php-js-css-web-toolkit-app/public

	<Directory "/absolute/path/to/php-js-css-web-toolkit-app/public">
		Options Indexes FollowSymlinks
		AllowOverride All
		Require all granted
	</Directory>

	# Use PHP-FPM (you need to a2enmod proxy_fcgi)
	#<FilesMatch \.php$>
	#	SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost"
	#</FilesMatch>

	# Proxy to the websockets.php server (you need to a2enmod proxy_wstunnel)
	#RewriteEngine on
	#RewriteCond %{REQUEST_URI} !/ws$
	#RewriteCond %{HTTP:Upgrade} websocket [NC]
	#RewriteCond %{HTTP:Connection} Upgrade [NC]
	#RewriteRule .* ws://localhost:8081%{REQUEST_URI} [P,L]

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

# nginx configuration
Development:
```
server {
	listen 8080;
	listen [::]:8080;

	root /absolute/path/to/php-js-css-web-toolkit-app/public;
	index index.php index.html;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php;

		# Disallow directory listing
		try_files $uri /index.php;
	}

	# Proxy to the websockets.php server
	#location /ws {
	#	if ($http_connection != "Upgrade") {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	set $or 0;
	#	if ($http_upgrade = "websocket") {
	#		set $or 1;
	#	}
	#	if ($http_upgrade = "WebSocket") {
	#		set $or 1;
	#	}
	#	if ($or = 0) {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	# via TCP
	#	proxy_pass http://127.0.0.1:8081;
	#	# or via Unix Domain Socket
	#	proxy_pass http://unix:/path/to/websockets.sock;

	#	proxy_http_version 1.1;
	#	proxy_set_header Upgrade $http_upgrade;
	#	proxy_set_header Connection "Upgrade";
	#	proxy_set_header Host $host;
	#}

	location ~ \.php$ {
		# snippets/fastcgi-php.conf;
		try_files $fastcgi_script_name /index.php;
		fastcgi_split_path_info ^(.+?\.php)(/.*)$;
		set $path_info $fastcgi_path_info;
		fastcgi_param PATH_INFO $path_info;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi.conf;

		fastcgi_pass unix:/run/php/php7.4-fpm.sock;

		# Remove the following code
		# if the application is to be
		# behind a proxy server
		fastcgi_param HTTP_X_FORWARDED_PROTO "";
		fastcgi_param HTTP_X_FORWARDED_HOST "";
		fastcgi_param HTTP_X_FORWARDED_PORT "";
		fastcgi_param HTTP_X_REAL_IP "";
	}

	location ~ /\.ht {
		rewrite ^ /index.php last;
	}
}
```

HTTP only:
```
server {
	listen 80;
	listen [::]:80;

	root /absolute/path/to/php-js-css-web-toolkit-app/public;
	index index.php index.html;

	server_name myapp.com www.myapp.com;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php;

		# Disallow directory listing
		try_files $uri /index.php;
	}

	# Proxy to the websockets.php server
	#location /ws {
	#	if ($http_connection != "Upgrade") {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	set $or 0;
	#	if ($http_upgrade = "websocket") {
	#		set $or 1;
	#	}
	#	if ($http_upgrade = "WebSocket") {
	#		set $or 1;
	#	}
	#	if ($or = 0) {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	# via TCP
	#	proxy_pass http://127.0.0.1:8081;
	#	# or via Unix Domain Socket
	#	proxy_pass http://unix:/path/to/websockets.sock;

	#	proxy_http_version 1.1;
	#	proxy_set_header Upgrade $http_upgrade;
	#	proxy_set_header Connection "Upgrade";
	#	proxy_set_header Host $host;
	#}

	location ~ \.php$ {
		# snippets/fastcgi-php.conf;
		try_files $fastcgi_script_name /index.php;
		fastcgi_split_path_info ^(.+?\.php)(/.*)$;
		set $path_info $fastcgi_path_info;
		fastcgi_param PATH_INFO $path_info;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi.conf;

		fastcgi_pass unix:/run/php/php7.4-fpm.sock;

		# Remove the following code
		# if the application is to be
		# behind a proxy server
		fastcgi_param HTTP_X_FORWARDED_PROTO "";
		fastcgi_param HTTP_X_FORWARDED_HOST "";
		fastcgi_param HTTP_X_FORWARDED_PORT "";
		fastcgi_param HTTP_X_REAL_IP "";
	}

	location ~ /\.ht {
		rewrite ^ /index.php last;
	}
}
```

HTTPS only:
```
server {
	listen 80;
	listen [::]:80;

	server_name myapp.com www.myapp.com;
	return 301 https://$server_name$request_uri;
}
server {
	listen 443 ssl http2;
	listen [::]:443 ssl http2;

	#include snippets/ssl-myapp.com.conf;
		ssl_certificate /etc/letsencrypt/live/myapp.com/fullchain.pem;
		ssl_certificate_key /etc/letsencrypt/live/myapp.com/privkey.pem;
	#include snippets/ssl-params.conf;
		ssl_protocols TLSv1.2 TLSv1.3; # Requires nginx >= 1.13.0 else use TLSv1.2
		ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384;
		ssl_prefer_server_ciphers on;
		ssl_session_cache shared:SSL:10m;
		ssl_dhparam /etc/ssl/certs/dhparam.pem;
		ssl_ecdh_curve secp384r1; # Requires nginx >= 1.1.0

	ssl_session_cache shared:SSL:10m;
	ssl_session_timeout 10m;
	keepalive_timeout 70;
	add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

	root /absolute/path/to/php-js-css-web-toolkit-app/public;
	index index.php index.html;

	server_name myapp.com www.myapp.com;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php;

		# Disallow directory listing
		try_files $uri /index.php;
	}

	# Proxy to the websockets.php server
	#location /ws {
	#	if ($http_connection != "Upgrade") {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	set $or 0;
	#	if ($http_upgrade = "websocket") {
	#		set $or 1;
	#	}
	#	if ($http_upgrade = "WebSocket") {
	#		set $or 1;
	#	}
	#	if ($or = 0) {
	#		rewrite ^ /index.php last;
	#		break;
	#	}

	#	# via TCP
	#	proxy_pass http://127.0.0.1:8081;
	#	# or via Unix Domain Socket
	#	proxy_pass http://unix:/path/to/websockets.sock;

	#	proxy_http_version 1.1;
	#	proxy_set_header Upgrade $http_upgrade;
	#	proxy_set_header Connection "Upgrade";
	#	proxy_set_header Host $host;
	#}

	location ~ \.php$ {
		# snippets/fastcgi-php.conf;
		try_files $fastcgi_script_name /index.php;
		fastcgi_split_path_info ^(.+?\.php)(/.*)$;
		set $path_info $fastcgi_path_info;
		fastcgi_param PATH_INFO $path_info;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi.conf;

		fastcgi_pass unix:/run/php/php7.4-fpm.sock;

		# Remove the following code
		# if the application is to be
		# behind a proxy server
		fastcgi_param HTTP_X_FORWARDED_PROTO "";
		fastcgi_param HTTP_X_FORWARDED_HOST "";
		fastcgi_param HTTP_X_FORWARDED_PORT "";
		fastcgi_param HTTP_X_REAL_IP "";
	}

	location ~ /\.ht {
		rewrite ^ /index.php last;
	}
}
```
