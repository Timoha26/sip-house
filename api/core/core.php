<?php

class Core
{
    private $host;

    function __construct()
    {
        $this->host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    }

    public function internal_error($message, $details)
    {
        header('HTTP/1.1 500 Internal Server Error');
        header("Status: 500 Internal Server Error");
        header("Content-Type: application/json; charset=utf-8");
        echo(json_encode(array('message' => $message, 'details' => $details), JSON_NUMERIC_CHECK));
        exit();
    }

    public function not_found($message, $details)
    {
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header("Content-Type: application/json; charset=utf-8");
        echo(json_encode(array('message' => $message, 'details' => $details), JSON_NUMERIC_CHECK));
        exit();
    }
}