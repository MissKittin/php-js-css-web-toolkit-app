# Internal (e)xploiter error
When something went wrong  
Made without love

### Note
Throws an `ie_error_exception` on error

### Supported error codes
* `400` Bad Request
* `401` Unauthorized
* `403` Forbidden
* `404` The address does not exist
* `410` Gone

### Supported languages
* `en`
* `pl`

### Usage
```
require './app/com/ie_error/main.php';

// show 404 page (english, bright theme)
ie_error(404);

// or show 403 page (polish, bright theme)
ie_error(403, 'pl');

// or show 401 page (english, dark theme)
ie_error(401, 'en', true);
```

## Portability
This component does not use libraries, so it can be moved as is.
