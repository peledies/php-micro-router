# PHP Micro Router
This router is a super slim router that does not have any package requirements. It gives a very similar look and feel as the Laravel and Lumen routers but without all the unnecesary cruft.

It supports the following:
- get requests
- post requests
- put requests
- delete requests
- URL parameterization

## Rewirite engine required
> In your production environment you will want apache or nginx to rewrite the urls for you so they look pretty.

> When developing locally, you can skip this step

Add the following to your .htaccess file
``` ini
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```

## Local development
The following command will start a php server and point it to your `example.php` file.
> All urls will be demoed in the following format `http://localhost:8000/api/example`
```
php -S localhost:8000 example.php
```

## Usage
Require the router class in your php file
``` php
require(__DIR__."/class.router.php");
```

Create a new instance of the router
``` php
$router = new Router();
```

Add routes to the router
``` php
$router->get('/api/example','example_one');
$router->get('/api/example/{id}','example_two');
```

Start the router
``` php
$router->start();
```

## See the `example.php` file for additonal examples