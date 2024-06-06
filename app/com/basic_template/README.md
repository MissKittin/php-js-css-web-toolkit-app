# Basic template
Skeleton of the page

## Required libraries
* `registry.php`

## Hint
You can use `$template->variable='value'` and `$template['variable']='value'` and setters.  
Pass `true` to the constructor to have the view method return the content instead of echoing it.

## Methods
* `set_variable(string_variable, value)`  
	add value to registry
* `add_csp_header(string_section, string_value)`  
	where `string_section` is eg `'script-src'` and `string_value` is `'\'unsafe-hashes\''`
* `add_html_header(string_header)`  
	eg `<my-header-tag content="my-content">`
* `add_meta_name_header(string_name, string_content)`  
	`<meta name="string_name" content="string_content">`
* `add_meta_property_header(string_property, string_content)`  
	`<meta property="string_property" content="string_content">`
* `add_style_header(string_path)`  
	link rel="stylesheet"
* `add_script_header(string_path)`  
	script src after page content
* `view(string_view_path, string_page_content='page_content.php')`  
	load configuration files from `string_view_path` and run template with contents from `string_view_path/string_page_content`  
	note: if `string_page_content` ends with `.php`, require will be used instead of readfile
* [static] `quick_view(string_view_path, string_page_content='page_content.php')`  
	same as the `view()`, use when you don't need to set any additional variables  
	note: if `string_page_content` ends with `.php`, require will be used instead of readfile

## Variables
* `lang [string]`  
	`<html lang="">` and `<meta property="og:locale">`
* `title [string]`  
	`<title>` and `<meta property="og:title">`
* `csp_header [array_assoc]`  
	use `add_csp_header()`
* `meta_robots [string]`  
	(no)index,(no)follow
* `meta_description [string]`  
	`<meta name="description" property="og:description">`
* `meta_name [array_assoc]`  
	use `add_meta_name_header()`
* `meta_property [array_assoc]`  
	use `add_meta_property_header()`
* `html_headers [string]`  
	use `add_html_header()`
* `styles [array]`  
	use `add_style_header()`
* `script [array]`  
	use `add_script_header()`

## Favicon
Paste the headers into the `favicon.html` file in this directory.  
The content will be appended to the head section.
