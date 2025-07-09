# [1.1]

### Added

- `app_root_path.php` library
- `redis_instance.php` library
- JSON database model template
- Session model template
- `mkmigration.php` tool

### Fixed

- Added missing `require APP_LIB.'/app_session.php'` in `route_template.php`
- Added missing `require __DIR__.'/../../../lib/stdlib.php';` in database configurations
- Fixed `create_table([` and missing `use app\src\controllers\controller_template` bug in database migrations
- `app_template.php` library: fixed `if(!$this->do_return_content)` in `view` method
- Fixed `init_request_response` method in `controller_template.php`

### Changed

- Updated `basic_template` component
- Moved `maximebf_debugbar.php` library logic outside the application and added new options
- Added `app_session_mod_cookie` handler parameters in `app_session.php` library, now checks for `openssl` and `mbstring` extensions via `function_exists`
- Added methods for `<link>` header and cache support in `basic_template_config.php` library
- Added more `model_params()` in `controller_template.php`
- PHPRedis and Predis integrated via `redis_instance.php` library in `cache_model_template.php`
- Added `$table_name` attribute in `pdo_model_template.php`
- Added canonical link header and cache, configuration via `basic_template_config.php` library is now default in `view_template`
- Database configurations look for settings in environment variables
- Migrations get table name from controller (`model_params()`), added `pdo_cheat.php`
- `app_params_explode()` (`app_params.php` library) now supports printing all parameters
- `ob_adapter_filecache` (`ob_adapter.php` library) now caches HTTP headers

### Deprecated

- `clickalicious_memcached.php` library is no longer needed - use `clickalicious_memcached.php` library from toolkit
