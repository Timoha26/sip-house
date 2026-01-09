<?php
ini_set('display_errors', 1);

@session_start();
if (count($_SESSION) <= 0)
    @session_destroy();

setlocale(LC_ALL, 'ru_RU.UTF8', 'rus_RUS.UTF8', 'Russian_Russia.65001', 'russian.65001');
header('Content-Type: text/html; charset=UTF-8');

require_once 'php/bootstrap.php';