<?php


$query = rtrim($_SERVER['QUERY_STRING'], '/');

define("DEBUG", 1);
define('BASE_DIR', __DIR__);
define('ROOT', dirname(__DIR__));
define('BASE_URL', "http://localhost/blog/"); // for relative path "http://localhost/blog/" example

include BASE_DIR . "/functions.php";
new ErrorHandler('front');
require BASE_DIR . '/classes/Router.php';
require BASE_DIR . '/classes/Cache.php';

new Cache;

Router::add('^ajax$', ['action' => 'ajax', 'layout' => false]);
Router::add('^simple-api$', ['action' => 'simple-api', 'layout' => false]);

Router::add('^about$', ['action' => 'about', 'layout' => 'page_tpl']);
Router::add('^contact$', ['action' => 'contact', 'layout' => 'page_tpl']);
Router::add('^forgot$', ['action' => 'forgot', 'layout' => 'page_tpl']);
Router::add('^registration$', ['action' => 'registration', 'layout' => 'page_tpl']);
Router::add('^profile$', ['action' => 'profile', 'layout' => 'page_tpl']);


Router::add('^author$', ['action' => 'author', 'layout' => 'author_tpl']);
Router::add('^categories$', ['action' => 'categories', 'layout' => 'category_tpl']);
Router::add('^single$', ['action' => 'single', 'layout' => 'single_tpl']);

Router::add('^gallery$', ['action' => 'gallery', 'layout' => 'page_with_sidebar_tpl']);
Router::add('^search$', ['action' => 'search', 'layout' => 'page_with_sidebar_tpl']);
Router::add('^simpleAPI$', ['action' => 'simpleAPI', 'layout' => 'page_with_sidebar_tpl']);









//default routes
Router::add('^(?P<action>[a-z-]+)$');
Router::add('^$', ['action' => 'main']);


// debug(Router::getRoutes());
// debug(Router::getRoute());
// debug($query);
Router::dispatch($query);

?>
