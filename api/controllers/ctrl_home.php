<?php

class Ctrl_Home extends Ctrl
{
    function __construct()
    {
        $this->model = new Model_Home();
        $this->template_view = "app/dist/app/index.html";
    }

    function action_index()
    {
        if (!file_exists($this->template_view))
            echo "View [ " . $this->template_view . " ] NOT FOUND!!!";
        else
            include $this->template_view;
    }
}