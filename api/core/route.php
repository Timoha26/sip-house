<?php

class Route
{
    static function start()
    {
        $controller_name = 'Home';
        $action_name = 'index';
        $params = null;
        $url = $_SERVER['REQUEST_URI'];

        if (strpos($url, "?") !== false) {
            $url = explode("?", $url);

            $routes = explode('/', $url[0]);
        } else {
            $routes = explode('/', $url);
        }

        //print_r($routes);
        //print_r($_GET);

        if (!empty($routes[1]))
            $controller_name = $routes[1];

        if (isset($routes[2])) {
            if (is_numeric($routes[2]))
                $params = array_slice($routes, 2);
            else
                $action_name = $routes[2];
        }

        if (isset($routes[3]))
            $params = array_slice($routes, 3);

        $model_name = 'Model_' . $controller_name;
        $controller_name = 'Ctrl_' . $controller_name;
        $action_name = array('action', $action_name);

        $model_file = strtolower($model_name) . '.php';
        $model_path = "php/models/" . $model_file;

        if (file_exists($model_path))
            include "php/models/" . $model_file;

        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = "php/controllers/" . $controller_file;

        if (file_exists($controller_path))
            include "php/controllers/" . $controller_file;
        else
            //Route::ErrorPage404();
            echo "Нет контроллера для этого url [" . $url . "]";

        $controller = new $controller_name;

        if (!method_exists($controller, implode('_', $action_name))) {
            $params = array($action_name[1]);
            $action = 'action_index';
        } else {
            $action = implode('_', $action_name);
        }

        if (method_exists($controller, $action))
            $controller->$action($params);
        else
            //Route::ErrorPage404();
            echo "Нет метода [" . $action_name . "] в контроллере [" . $controller_name . "] для этого url [" . $url . "]";
    }

    function ErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }
}