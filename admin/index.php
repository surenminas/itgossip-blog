<?php


$query = rtrim($_SERVER['QUERY_STRING'], '/');

define("DEBUG", 1);
define('BASE_DIR', __DIR__);
define('ROOT', dirname(__DIR__));
define('ABSOLUTE_URL', "http://localhost/blog/admin/"); // for relative path "http://localhost/blog/" example



include ROOT . "/functions.php";
include ROOT . "/admin/admin_functions.php";
new ErrorHandler('admin');
require ROOT . '/classes/Router.php';
require ROOT . '/classes/Cache.php';

new Cache;



//default routes
Router::add('^(?P<action>[a-z-]+)$', ['prefix'=> 'admin', 'layout' => 'admin_tpl']);
Router::add('^$', ['action' => 'home', 'prefix'=> 'admin', 'layout' => 'admin_tpl']);



// debug(Router::getRoutes());
// debug(Router::getRoute());
// debug($query);
Router::dispatch($query);
