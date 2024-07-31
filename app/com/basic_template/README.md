# Basic template
Skeleton of the page

## Required libraries
* `registry.php`

## Note
Throws an `basic_template_exception` on error

## Hint
You can use `$template->variable='value'` and `$template['variable']='value'` and setters.  
Pass `true` to the constructor to have the view method return the content instead of echoing it.

## Methods
* `add_csp_header(string_section, string_value)` [returns self]  
	where `string_section` is eg `'script-src'` and `string_value` is `'\'unsafe-hashes\''`
* `add_html_header(string_header)` [returns self]  
	eg `<my-header-tag content="my-content">`
* `add_meta_name_header(string_name, string_content)` [returns self]  
	`<meta name="string_name" content="string_content">`
* `add_meta_property_header(string_property, string_content)` [returns self]  
	`<meta property="string_property" content="string_content">`
* `add_style_header(string_path)` [returns self]  
	`link rel="stylesheet"`
* `add_script_header(string_path)` [returns self]  
	`script src` after page content
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
	compiles styles and scripts and adds them to the inline tag instead of `link rel="stylesheet"` and `script src=""` (not recommended)  
	default: `false`
* `set_variable(string_variable, value)` [returns self]  
	add value to registry  
	see [Variables](#variables)
* `view(string_view_path, string_page_content='page_content.php')` [returns `null`|`string_content`]  
	load configuration files from `string_view_path` and run template with contents from `string_view_path/string_page_content`  
	**note:** if `string_page_content` ends with `.php`, require will be used instead of readfile
* **[static]** `quick_view(string_view_path, string_page_content='page_content.php')`  
	same as the `view()`, use when you don't need to set any additional variables  
	**note:** if `string_page_content` ends with `.php`, require will be used instead of readfile

## Variables
* `lang` [string]  
	`<html lang="">` and `<meta property="og:locale">`
* `title` [string]  
	`<title>` and `<meta property="og:title">`
* `csp_header` [assoc array]  
	use `add_csp_header()`
* `meta_robots` [string]  
	(no)index,(no)follow
* `meta_description` [string]  
	`<meta name="description" property="og:description">`
* `meta_name` [assoc array]  
	use `add_meta_name_header()`
* `meta_property` [assoc array]  
	use `add_meta_property_header()`
* `html_headers` [string]  
	use `add_html_header()`
* `styles` [array]  
	use `add_style_header()`
* `script` [array]  
	use `add_script_header()`
