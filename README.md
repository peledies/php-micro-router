

###Rewirite engine required

Add the following to your .htaccess file
```
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```

###Usage
Require the router class in your index.php
```
require("class.router.php");
```

Create a new instance of the router
```
$router = new Router();
```

Add routes to the router
```
$router->get(array('api/example/all','some_function'));
$router->post(array('api/example/value','some_function_2'));
$router->put(array('api/example/mode','some_function_3'));
$router->delete(array('api/example/delete','some_function_4'));
```

Start the router
```
$router->execute();
```