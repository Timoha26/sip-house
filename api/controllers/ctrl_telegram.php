<?php

class Ctrl_Telegram extends Ctrl
{
    function __construct()
    {
        $this->model = new Model_Telegram();
    }

    function action_index()
    {

    }

    function action_sent_message()
    {
        $data = json_decode(trim(file_get_contents('php://input')));
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $result = $this->model->register_message($data, $user_agent);

        header("Content-Type: application/json; charset=utf-8");
        print_r(json_encode($result));
    }
}