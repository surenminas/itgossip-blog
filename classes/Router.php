<?php

class Router {

    protected static $routes = [];
    protected static $route = [];

    public static function add($regexp, $route = []) {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes() {
        return self::$routes;
    }
    
    public static function getRoute() {
        return self::$route;
    }

    public static function matchRoute($url) {
        foreach(self::$routes as $pattern => $route) {
            if(preg_match("#$pattern#i", $url, $matches)) {
                foreach($matches as $k => $v) {
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if(!isset($route['layout'])) {
                    $route['layout'] = 'main_tpl';
                } 

                //prefix for admin controller
                if(!isset($route['prefix'])) {
                    $route['prefix'] = '';
                }else{
                    $route['prefix'] .= '\\';
                }
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    public static function dispatch($url) {
        $url = self::removeQueryString($url);
        if(self::matchRoute($url)) {
            $action =  self::$route['action'] . ".php";
            if(file_exists($action)) {
                if(self::$route['layout'] === false) {
                    include $action;
                }else{
                //ob start
                    ob_start();
                        include $action;
                        $content = ob_get_contents();
                    ob_clean();
                //ob clear
                if(!self::$route['prefix']) {
                    require BASE_DIR . '/blocks/layouts/'. self::$route['layout'] .'.php';
                } else {
                    require ROOT . '/blocks/layouts/'. self::$route['layout'] .'.php';
                }
                //
                }
                
            } else {
                // http_response_code(404);
                // include BASE_DIR . '\404.php';
                throw new \Exception("Page " . $action ." not found", 404);

            }           
        } else {
            // http_response_code(404);
            // include BASE_DIR . '\404.php';
            throw new \Exception("Page not found", 404);
        }
    }

    protected static function removeQueryString($url) {
        if(!empty($url)){
            $params = explode('&', $url, 2);
            if(false === strpos($params[0], '=')){
                return rtrim($params[0], '/');
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
}