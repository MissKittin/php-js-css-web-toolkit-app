* [Toolkit as a composer package](#toolkit-as-a-composer-package)
* [A few words about design](#a-few-words-about-design)
	* [Application standard library](#application-standard-library)
* [Creating The project](#creating-the-project)
	* [GNU GPL](#gnu-gpl)
	* [Hint - git](#hint---git)
	* [Hint - index.php](#hint---indexphp)
	* [Testing toolkit](#testing-toolkit)
	* [Example application](#example-application)
	* [Removing example application](#removing-example-application)
	* [Removing documentation](#removing-documentation)
	* [PHP polyfill Component Cache](#php-polyfill-component-cache)
	* [JavaScript polyfills](#javascript-polyfills)
	* [Packing the toolkit into Phar](#packing-the-toolkit-into-phar)
	* [Installing Composer](#installing-composer)
	* [Installing Predis](#installing-predis)
* [How to create application](#how-to-create-application)
	* [Templates](#templates)
	* [Tests](#tests)
	* [Autoloading](#autoloading)
	* [Configuring URL routing](#configuring-url-routing)
	* [Creating assets](#creating-assets)
	* [Sass von der Less](#sass-von-der-less)
	* [Creating database configuration for pdo_connect()](#creating-database-configuration-for-pdo_connect)
	* [Queue worker](#queue-worker)
	* [WebSockets](#websockets)
	* [Compiling assets](#compiling-assets)
	* [Minifying assets - webdev.sh client](#minifying-assets---webdevsh-client)
	* [Minifying assets - matthiasmullie's minifier](#minifying-assets---matthiasmullies-minifier)
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
Toolkit was not intended as a Composer package, but such usage is possible, e.g.:
```
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "misskittin/php-js-css-web-toolkit",
                "description": "MissKittin web toolkit",
                "homepage": "https://github.com/MissKittin/php-js-css-web-toolkit/",
                "authors": [
                    {
                        "name": "MissKittin@GitHub",
                        "homepage": "https://github.com/MissKittin"
                    }
                ],
                "type": "library",
                "license": "LGPL-3.0-only",
                "version": "1",
                "autoload": {
                    "files": [
                        "com/php_polyfill/main.php",
                        "autoload.php"
                    ]
                },
                "dist": {
                    "url": "https://github.com/MissKittin/php-js-css-web-toolkit/archive/refs/tags/stable.zip",
                    "type": "zip"
                },
                "require": {
                    "php": ">=7.1"
                }
            }
        }
    ],
    "scripts": {
        "pre-autoload-dump": [
            "@mktk remove-gpl -- --yes",
            "@mktk strip-php-files -- ./vendor/misskittin/php-js-css-web-toolkit --remove-tests --remove-md",
            "@mktkc php_polyfill mkcache",
            "@mktk autoloader-generator --in ./vendor/misskittin/php-js-css-web-toolkit/com --in ./vendor/misskittin/php-js-css-web-toolkit/lib --ignore bin/ --out ./vendor/misskittin/php-js-css-web-toolkit/autoload.php"
        ],
        "mktk": "@php -r \"$_bin=$argv[1]; array_shift($argv); array_shift($argv); if(is_file('./vendor/misskittin/php-js-css-web-toolkit/bin/'.$_bin.'.php')) require './vendor/misskittin/php-js-css-web-toolkit/bin/'.$_bin.'.php'; else exit(1);\"",
        "mktkc": "@php -r \"$_com=$argv[1]; $_bin=$argv[2]; array_shift($argv); array_shift($argv); array_shift($argv); if(is_file('./vendor/misskittin/php-js-css-web-toolkit/com/'.$_com.'/bin/'.$_bin.'.php')) require './vendor/misskittin/php-js-css-web-toolkit/com/'.$_com.'/bin/'.$_bin.'.php'; else exit(1);\""
    },
    "require": {
        "misskittin/php-js-css-web-toolkit": "*"
    }
}

```
or if you also want extras:
```
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "misskittin/php-js-css-web-toolkit",
                "description": "MissKittin web toolkit",
                "homepage": "https://github.com/MissKittin/php-js-css-web-toolkit/",
                "authors": [
                    {
                        "name": "MissKittin@GitHub",
                        "homepage": "https://github.com/MissKittin"
                    }
                ],
                "license": "LGPL-3.0-only",
                "version": "1",
                "autoload": {
                    "files": [
                        "com/php_polyfill/main.php",
                        "autoload.php"
                    ]
                },
                "dist": {
                    "url": "https://github.com/MissKittin/php-js-css-web-toolkit/archive/refs/tags/stable.zip",
                    "type": "zip"
                },
                "require": {
                    "php": ">=7.1"
                }
            }
        },
        {
            "type": "package",
            "package": {
                "name": "misskittin/php-js-css-web-toolkit-extras",
                "description": "MissKittin web toolkit extras",
                "homepage": "https://github.com/MissKittin/php-js-css-web-toolkit-extras/",
                "authors": [
                    {
                        "name": "MissKittin@GitHub",
                        "homepage": "https://github.com/MissKittin"
                    }
                ],
                "license": "LGPL-3.0-only",
                "version": "1",
                "dist": {
                    "url": "https://github.com/MissKittin/php-js-css-web-toolkit-extras/archive/refs/tags/stable.zip",
                    "type": "zip"
                },
                "require": {
                    "php": ">=7.1"
                }
            }
        }
    ],
    "scripts": {
        "pre-autoload-dump": [
            "@mktk remove-gpl -- --yes",
            "@mktk strip-php-files -- ./vendor/misskittin/php-js-css-web-toolkit --remove-tests --remove-md",
            "@mktk strip-php-files -- ./vendor/misskittin/php-js-css-web-toolkit-extras --remove-tests --remove-md",
            "@mktkc php_polyfill mkcache",
            "@mktk autoloader-generator --in ./vendor/misskittin/php-js-css-web-toolkit/com --in ./vendor/misskittin/php-js-css-web-toolkit/lib --in ./vendor/misskittin/php-js-css-web-toolkit-extras/lib --ignore bin/ --out ./vendor/misskittin/php-js-css-web-toolkit/autoload.php"
        ],
        "mktk": "@php -r \"$_bin=$argv[1]; array_shift($argv); array_shift($argv); if(is_file('./vendor/misskittin/php-js-css-web-toolkit/bin/'.$_bin.'.php')) require './vendor/misskittin/php-js-css-web-toolkit/bin/'.$_bin.'.php'; else if(is_file('./vendor/misskittin/php-js-css-web-toolkit-extras/bin/'.$_bin.'.php')) require './vendor/misskittin/php-js-css-web-toolkit-extras/bin/'.$_bin.'.php'; else exit(1);\"",
        "mktkc": "@php -r \"$_com=$argv[1]; $_bin=$argv[2]; array_shift($argv); array_shift($argv); array_shift($argv); if(is_file('./vendor/misskittin/php-js-css-web-toolkit/com/'.$_com.'/bin/'.$_bin.'.php')) require './vendor/misskittin/php-js-css-web-toolkit/com/'.$_com.'/bin/'.$_bin.'.php'; else exit(1);\""
    },
    "require": {
        "misskittin/php-js-css-web-toolkit": "*",
        "misskittin/php-js-css-web-toolkit-extras": "*"
    }
}

```
If your project is GPL licensed, you can remove the first line in the `scripts` section.  
PHP does not have an autoloader for functions, so you have to load the function manually:
```
<?php
	load_function('copy_recursive'); // invoke this once
	copy_recursive($src, $dest);
?>
```

# A few words about design
The application design does not define the paradigm in which individual parts of the application will be written.  
You decide what will be written procedurally, functionally and object-oriented, as well as what type of routing you will use.  
It has its advantages and disadvantages - it is not a typical framework, so think about whether you need something like Symfony or Laravel.  
**Remember about safety!** Treat all data coming from the user as untrusted. Filter them before sending them back. **Keep your code clean.**

### Application standard library
The `app/lib/stdlib.php` library is the parent library of the application - it is loaded by the `app/entrypoint.php`.  
Provides constants with paths to specific parts of the application.  
If you pack the toolkit into a phar, the library will set the paths so that files are loaded from the archive.  
The library also includes an application bootstrap that creates a `var` directory hierarchy.  
Additionally, it provides classes:
* `app_exception`

		throw new app_exception('Fehler!');

* `app_env`  
	that uses `dotenv.php` library  
	see [app README DotEnv](app/README.md#dotenv)

		$env_var=app_env::getenv('ENV_VAR_NAME', 'default_value');


# Creating The project
```
git clone --recursive --depth 1 --shallow-submodules -b stable "https://github.com/MissKittin/php-js-css-web-toolkit-app.git" my-awesome-project
```
To add unofficial extras, run:
```
git clone --depth 1 "https://github.com/MissKittin/php-js-css-web-toolkit-extras.git" tke
```

### GNU GPL
It happens that GPL license is not wanted - if you link to a GPL-licensed library, your project must also be GPL-licensed.  
If you are in such a situation, you can remove them:
```
php ./tk/bin/remove-gpl.php --yes
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
export TEST_REDIS_HOST=127.0.0.1 # default
export TEST_REDIS_SOCKET=/var/run/redis/redis.sock # has priority over the HOST
export TEST_REDIS_PORT=6379 # default
export TEST_REDIS_DBINDEX=0 # default
export TEST_REDIS_USER=myuser # not set by default
export TEST_REDIS_PASSWORD=mypass # not set by default

export TEST_MEMCACHED=yes # default: no
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

### Example application
Instructions on how to run the sample application are in [app README](app/README.md)  
After configuring the application, [start the HTTP server](#running-dev-server)  
If you want to create your own app, read on.

### Removing example application
All example application code is in `samples` dirs.  
Remove samples and start developing application: run:
```
php ./app/bin/remove-samples.php
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

### Packing the toolkit into Phar
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

### Installing Composer
To install Composer, run:
```
php ./tk/bin/get-composer.php
```
Composer will be installed in `./tk/bin/composer.phar`

### Installing Predis
Predis is supported but not directly - the PHPRedis API (`redis` PHP extension) is used by default.  
For Predis, a `predis_phpredis_proxy` class from the `predis_connect.php` library is needed.  
To install Predis, run:
```
php ./tk/bin/composer.phar --optimize-autoloader --no-cache require predis/predis
```
For more information, see the `predis_connect.php` library  
**Note:** Predis requires PHP >= 7.2, for PHP >= 5.3.9 use Predis v1.1.10  
**Hint:** It is possible to install Predis without Composer using Git (not recommended):
```
php -r "file_put_contents('./composer.json', '');"
git clone "https://github.com/predis/predis.git" ./vendor
```

# How to create application

### Templates
Copy them and start creating.
* `app/src/controllers/controller_template.php`
* `app/src/models/model_template.php`
* `app/src/routes/route_template.php`
* `app/src/views/view_template`
* `app/tests/ns_test_template.php` - allows you to create mock PHP functions and classes
* `app/tests/test_template.php`

### Tests
`app/tests` has the same layout as `app/src` - tests are divided into individual sections.  
Copy the test template to a similar location as in `app/src` and rename the file to the same name as the file being tested.  
If you need a mock built-in function or class, use the `ns_test_template.php` template.  
Then edit `$stdlib_path`, add required libraries and put testing code in `// test body` section.  
To run all tests at once use the `app/bin/run-php-tests.php` tool.

### Autoloading
You can use the `autoloader-generator.php` tool to generate an autoloader script for functions and classes.  
For more info, run:
```
php ./tk/bin/autoloader-generator.php
```

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
php ./tk/bin/composer.phar require scssphp/scssphp
php ./tk/bin/composer.phar require leafo/lessphp
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

### Minifying assets - webdev.sh client
```
php ./tk/bin/webdev.sh --dir ./public/assets
```
All css and js files in `public/assets` will be minified.

### Minifying assets - matthiasmullie's minifier
The way of using `./tk/bin/matthiasmullie-minify.php` is the same as in the webdev.sh client.  
**Note:** before use, you need to install the composer and minifier package:
```
mkdir ./tk/bin/composer
php ./tk/bin/composer.phar --optimize-autoloader --no-cache --working-dir=./tk/bin/composer require matthiasmullie/minify
```

### Seeding database offline with pdo_connect() (optional)
To offline seed database, run:
```
php ./tk/bin/pdo-connect.php --db ./app/databases/database-name
```
**Note:** database can be seeded automatically on first start.

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
To take advantage of the possibility of preloading the application,  
you can generate the preloader script using the `opcache-preload-generator.php` tool.  
For more info, run:
```
php ./tk/bin/opcache-preload-generator.php
```
However, if you use an autoloader, this method may not be optimal. You will need to write your own preloader:  
* if the script only defines a constants, functions, classes, traits or interfaces, use `require`
* in other cases use `opcache_compile_file`

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
**Note:** all routings and asset paths in views must be appropriate:  
**Hint:** just modify the switch argument in `app/entrypoint.php`:
```
// add a slash to $_SERVER['REQUEST_URI']
// and increase the explode key according to the number of subdirectories
switch(explode('/', strtok($_SERVER['REQUEST_URI'].'/', '?'))[2])
```

* hosting with `public_html`
	1. mkdir `./public_html` and `./your-app`
	2. move `./public` to `./public_html` and rename to `app-name`
	3. move everything to `./your-app` except `./public_html`
	4. edit `./public_html/app-name/index.php` and correct the require path  
		(here: `require '../../your-app/app/entrypoint.php';`)
	5. test the application:

			php ./your-app/tk/bin/serve.php --docroot ./public_html

		and open a web browser: `http://127.0.0.1:8080/app-name/`
	6. upload `./public_html/app-name` directory to `public_html/app-name`  
		(where the second `public_html` is document root in your hosting)
	7. upload `./your-app` to `your-app`  
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
	7. upload project to `my-app`


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
	index index.php index.html index.htm;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php?$query_string;

		# Disallow directory listing
		try_files $uri /index.php?$query_string;
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
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php-fpm.sock;

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
	index index.php index.html index.htm;

	server_name myapp.com www.myapp.com;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php?$query_string;

		# Disallow directory listing
		try_files $uri /index.php?$query_string;
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
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php-fpm.sock;

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

	include snippets/ssl-myapp.com.conf;
	include snippets/ssl-params.conf;

	ssl_session_cache shared:SSL:10m;
	ssl_session_timeout 10m;
	keepalive_timeout 70;
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
	ssl_ciphers HIGH:!aNULL:!MD5;
	add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

	root /absolute/path/to/php-js-css-web-toolkit-app/public;
	index index.php index.html index.htm;

	server_name myapp.com www.myapp.com;

	# Enable directory listing
	#autoindex on;

	location / {
		# Allow directory listing
		#try_files $uri $uri/ /index.php?$query_string;

		# Disallow directory listing
		try_files $uri /index.php?$query_string;
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
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php-fpm.sock;

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
