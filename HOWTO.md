# Creating The project
```
git clone --recursive --depth 1 --shallow-submodules -b stable "https://github.com/MissKittin/php-js-css-web-toolkit-app.git" my-awesome-project
```
To add unofficial extras, run:
```
git clone --depth 1 "https://github.com/MissKittin/php-js-css-web-toolkit-extras.git" tke
```
**Hint:** you won't need git anymore - you can delete the git repo data: run  
for *nix:
```
rm -rf .git; rm .gitmodules; rm tk/.git; rm -rf tke/.git
```
for windows:
```
rd /s /q .git && del .gitmodules && del tk\.git && rd /s /q tke\.git
```
**Hint:** `public/index.php` just imports another php file - this is stupid thing if your OS allows you to use softlinks.  
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
php ./tk/bin/run-php-bin-tests.php ./tke/bin
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

### PHP polyfill Component Cache
This component includes `pf_*.php` libraries depending on PHP version.  
But what if there was one file instead of many?
```
php ./tk/com/php_polyfill/bin/mkcache.php
php ./tk/com/php_polyfill/bin/destroy.php
```
I gra gitara :)

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
require 'phar://'
.	'./tk.phar'
.	'/com/admin_panel/admin_panel.php';
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


# How to create application

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

### Creating database configuration for pdo_connect()
See `pdo_connect.php` library.

### Queue worker
It is better to do more time-consuming tasks outside the main application.  
The `queue_worker.php` library and the `queue-worker.php` tool are available for this purpose.  
**Note:** this library uses the features of *nix systems and is only intended for them.  
For more info, see `./tk/lib/queue_worker.php` and run:
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

### Preloading application
To take advantage of the possibility of preloading the application,  
you can generate the preloader script using the `opcache-preload-generator.php` tool.  
For more info, run:
```
php ./tk/bin/opcache-preload-generator.php
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
