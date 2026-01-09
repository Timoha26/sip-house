<?php

class Model
{
    public $db;
    public $limit_pages;

    function __construct()
    {
        $this->core = new Core();
        $this->limit_pages = 10;
    }

    public function set_limit($limit)
    {
        $this->limit_pages = $limit;
    }

    public function db_open()
    {
        $this->db = new mysqli(mysql_hostname, mysql_username, mysql_password, mysql_database);
        if ($this->db->connect_errno) {
            return false;
        } else {
            $this->db->query("SET NAMES utf8;");
            return true;
        }
    }

    public function db_close()
    {
        $this->db->close();
    }

    public function get_data()
    {

    }
}