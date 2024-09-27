# Basic template
Skeleton of the page

## Required libraries
* `generate_csp_hash.php`
* `rand_str.php`
* `registry.php`

## Note
Throws an `basic_template_exception` on error

## Methods
* `__construct(bool_return_content=false)`  
	if `bool_return_content` is set to `true`, the `view` method will return the content instead of echoing it
* `add_csp_header(string_section, string_value)` [returns self]  
	where `string_section` is eg `'script-src'` and `string_value` is `'\'unsafe-hashes\''`
* `add_html_header(string_header)` [returns self]  
	eg `<my-header-tag content="my-content">`
* `add_meta_name_header(string_name, string_content)` [returns self]  
	`<meta name="string_name" content="string_content">`
* `add_meta_property_header(string_property, string_content)` [returns self]  
	`<meta property="string_property" content="string_content">`
* `add_og_header(string_property, string_content)` [returns self]  
	`<meta property="og:string_property" content="string_content">`
* `add_style_header(string_path, string_integrity_hash=null, string_crossorigin='anonymous')` [returns self]  
	`<link rel="stylesheet"`  
	if `string_integrity_hash` is not `null`, adds the `integrity` and `crossorigin` parameters for Subresource Integrity function
* `add_script_header(string_path, string_integrity_hash=null, string_crossorigin='anonymous')` [returns self]  
	`script src` after page content  
	if `string_integrity_hash` is not `null`, adds the `integrity` and `crossorigin` parameters for Subresource Integrity function
* `add_inline_style(string_content, bool_add_csp_hash=true, bool_add_csp_nonce=false)` [returns self]  
	add `<style>` block and generate hash or nonce for it  
	**note:** `add_csp_hash` takes priority over `add_csp_nonce`  
	**warning:** if `add_csp_hash` is used, the `<style>` block cannot be postprocessed  
	**warning:** if `add_csp_nonce` is used, the page should not be cached
* `add_inline_script(string_code, bool_add_csp_hash=true, bool_add_csp_nonce=false)` [returns self]  
	add `<script>` block and generate hash or nonce for it  
	**note:** `add_csp_hash` takes priority over `add_csp_nonce`  
	**warning:** if `add_csp_hash` is used, the `<script>` block cannot be postprocessed  
	**warning:** if `add_csp_nonce` is used, the page should not be cached
* **[static]** `disable_default_styles(bool_value=true)` [returns self]  
	disable default template styles (use `disable_default_styles(false)` to re-enable)
* **[static]** `disable_default_scripts(bool_value=true)` [returns self]  
	disable default template scripts (use `disable_default_scripts(false)` to re-enable)
* **[static]** `set_assets_path(string_path)` [returns self]  
	set the url to the assets directory  
	default: `/assets`
* **[static]** `set_assets_filename(string_name)` [returns self]  
	the `basic-template.css` and `basic-template.js` files can be renamed  
	set filename for assets  
	default: `basic-template`
* **[static]** `set_favicon(string_path)` [returns self]  
	path to the favicon headers file  
	the content will be appended to the `<head>` section
* **[static]** `set_inline_assets(bool_option)` [returns self]  
	compiles styles and scripts and adds them to the inline tag instead of `<link rel="stylesheet"` and `<script src=""` (not recommended)  
	default: `false`
* `set_variable(string_variable, value)` [returns self]  
	add value to registry  
	see [Variables](#variables)
* `view(string_view_path, string_page_content='page_content.php')` [returns `null`|`string_content`]  
	load configuration files from `string_view_path` and run template with contents from `string_view_path/string_page_content`  
	**note:** if `string_page_content` ends with `.php`, `require` will be used instead of `readfile`
* **[static]** `quick_view(string_view_path, string_page_content='page_content.php')`  
	same as the `view()`, use when you don't need to set any additional variables  
	**note:** if `string_page_content` ends with `.php`, `require` will be used instead of `readfile`

## Variables
You can use `$template->variable='value'`, `$template['variable']='value'` and [setters](#methods)

* `lang` [string] (eg. `en_US`)  
	`<html lang="en">` (via `strtok($lang, '_')`) and `<meta property="og:locale" content="en_US">` (can be overwritten by calling `add_og_header()`)
* `head_prefix` [string]  
	`<head prefix="string">`
* `title` [string]  
	`<title>` and `<meta property="og:title">` (can be overwritten by calling `add_og_header()`)
* `csp_header` [assoc array]  
	use `add_csp_header()`
* `opengraph_headers` [assoc array]  
	use `add_og_header()`
* `meta_robots` [string]  
	`index,follow` or  
	`noindex,nofollow`
* `meta_description` [string]  
	`<meta name="description" property="og:description">` or  
	`<meta name="description">` if `add_og_header()` called
* `meta_name` [assoc array]  
	use `add_meta_name_header()`
* `meta_property` [assoc array]  
	use `add_meta_property_header()`
* `html_headers` [string]  
	use `add_html_header()`
* `styles` [arrays][`string_path`, `string_integrity_hash`|`null`, `string_crossorigin`]  
	use `add_style_header()`
* `script` [arrays][`string_path`, `string_integrity_hash`|`null`, `string_crossorigin`]  
	use `add_script_header()`

## View layout
Create a new directory, e.g. `my_view` and add the required files to it.

`template_config.php` (required):
```
<?php
	// CSP
	$view['csp_header']['script-src'][]='\'sha256-hash\'';
	$view['csp_header']['style-src'][]='\'sha256-hash\'';

	// basic settings
	$view['lang']='en_US';
	$view['title']='Page title';
	$view['meta_description']='Page description';
	$view['meta_robots']='index,follow';

	// additional settings
	$view['meta_name']['my_meta_name']='my_meta_content';
	$view['meta_property']['my_meta_property']='my_meta_content';
	$view['html_headers'].='<tag>content</tag>'; // note: .= can "PHP Notice:  Undefined variable $view['html_headers']"
	//static::$favicon=__DIR__.'/favicon.html';

	// Open Graph headers
	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'My Awesome Website'];
	//$view['opengraph_headers'][]=['image', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER[HTTP_HOST].'/assets/website-logo.jpg'];
	//$view['opengraph_headers'][]=['image:type', 'image/jpeg'];
	//$view['opengraph_headers'][]=['image:width', '400'];
	//$view['opengraph_headers'][]=['image:height', '300'];
	//$view['opengraph_headers'][]=['image:alt', 'Red Bone'];
	//$view['opengraph_headers'][]=['locale:alternate', 'fr_FR'];

	// custom styles
	$view['styles'][]=['/assets/myStyle.css', null, null]; // or ['https://another.server/myStyle.css', 'sha384-hash', 'anonymous']
	$view['scripts'][]=['/assets/myScript.js', null, null]; // or ['https://another.server/myScript.js', 'sha384-hash', 'anonymous']

	// user-defined functions and data

	$view['my_function']=function($text)
	{
		return '<span style="color: red;">'.$text.'</span>'; // you can echo this
	};

	$view['my_variable']='my value';

	// HACK! you can set a different name for the page_content.php file
	// but I advise against such maneuvers!
	//$page_content='renamed_page_content.php'
?>
```

`page_content.php` (can be renamed):
```
<h1>My <?php echo $view['my_function']('variable'); ?> has <?php echo $view['my_variable']; ?></h1>
```

`custom_file.html` (optional):
```
<h1>This line will be the header</h1>
This code <?php echo 'will leak'; ?>
```

## Usage
Full view:
```
<?php
	require APP_COM.'/basic_template/main.php';

	$template=new basic_template();

	// some tasks here

	$template->view('path/to/my_view'); // process the page_content.php file
	// or
	$template->view('path/to/my_view', , 'custom_file.html'); // readfile custom_file.html
?>
```

Quick view:
```
<?php
 	require APP_COM.'/basic_template/main.php';

	basic_template::quick_view('path/to/my_view'); // process the page_content.php file
	// or
	basic_template::quick_view('path/to/my_view', 'custom_file.html'); // readfile custom_file.html
?>
```

## Integration with Bootstrap
Just add options to `template_config.php`, eg:
```
// allow cdn.jsdelivr.net
$view['csp_header']['style-src'][]='https://cdn.jsdelivr.net';
$view['csp_header']['script-src'][]='https://cdn.jsdelivr.net';

// you can disable default template styles and scripts
static
::	disable_default_styles()
::	disable_default_scripts();

// add bootstrap assets
$view['styles'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65', 'anonymous'];
$view['scripts'][]=['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4', 'anonymous'];
```
or if you want to host bootstrap resources on your server:
```
// you can disable default template styles and scripts
static
::	disable_default_styles()
::	disable_default_scripts();

// add bootstrap assets
$view['styles'][]=['/assets/bootstrap.min.css', null, null];
$view['scripts'][]=['/assets/bootstrap.bundle.min.js', null, null];
```

## Portability
This component is part of the application and is not designed for use outside of it.
