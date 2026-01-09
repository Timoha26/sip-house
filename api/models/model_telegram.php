<?php

class Model_Telegram extends Model
{
    public function register_message($data, $user_agent)
    {
        $result = array();

        $this->add_new_message($data->name, $data->model, $data->year, $data->phone, $user_agent);

        if (empty($data->phone) || trim($data->phone) == "")
            return $result;

        $chats = $this->get_chats();
        $message = $this->generate_message($data);
        foreach ($chats as $chat)
            if ($chat->active)
                $result[] = $this->send_message($chat->chat_id, $message);

        return $result;
    }

    private function get_chats()
    {
        $chats_from_telegram = $this->get_chats_from_telegram();
        $chats_from_db = $this->get_chats_from_db();

        foreach ($chats_from_telegram as $chat)
            if (array_search($chat->chat_id, array_column($chats_from_db, "chat_id")) === false) {
                $this->add_chat_id($chat->chat_id, $chat->user_name);
                $chats_from_db[] = (object)[
                    "chat_id" => $chat->chat_id,
                    "user_name" => $chat->user_name
                ];
            }

        return $chats_from_db;
    }

    private function send_message($chat_id, $message)
    {
        $url = telegram_host . "/bot" . telegram_token . "/sendMessage";
        $data = (object)["chat_id" => $chat_id, "text" => $message, "parse_mode" => "HTML"];
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_data)
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    private function generate_message($data)
    {
        $result = ['Заявка с сайта.'];

        if ($data->name != null && strlen($data->name) > 0)
            array_push($result, "Марка: <b>" . $data->name . "</b>");

        if ($data->model && strlen($data->model) > 0)
            array_push($result, "Модель: <b>" . $data->model . "</b>");

        if ($data->year && strlen($data->year) > 0)
            array_push($result, "Год выпуска: <b>" . $data->year . "</b>");

        if ($data->phone && strlen($data->phone) > 0)
            array_push($result, "Телефон: <b>" . $data->phone . "</b>");

        return implode("\n", $result);
    }

    private function get_chats_from_db()
    {
        $this->db_open();

        $this->create_chats_table_is_not_exist();

        $this->update_chats_table();

        $query = "
			SELECT
				id,
				chat_id,
				user_name,
			    active
			FROM
				telegram_chats";

        $db_result = $this->db->query($query);

        $this->db->close();

        if (!$db_result)
            $this->core->internal_error('Ошибка сервера.', 'Произошла SQL ошибка на сервере.\n' . $this->db->error);

        $result = array();

        if ($db_result && $db_result->num_rows > 0)
            while ($row = $db_result->fetch_object())
                $result[] = (object)[
                    "chat_id" => $row->chat_id,
                    "user_name" => $row->user_name,
                    "active" => $row->active
                ];

        return $result;
    }

    private function add_chat_id($chat_id, $user_name)
    {
        $this->db_open();

        $this->create_chats_table_is_not_exist();

        $this->update_chats_table();

        $query = "INSERT INTO telegram_chats(chat_id, user_name) VALUES (" . $chat_id . ",'" . $user_name . "')";

        $db_result = $this->db->query($query);

        $this->db->close();

        if (!$db_result)
            $this->core->internal_error('Ошибка сервера.', 'Произошла SQL ошибка на сервере.\n' . $this->db->error);
    }

    private function get_chats_from_telegram()
    {
        $url = telegram_host . "/bot" . telegram_token . "/getUpdates";

        $response = json_decode(file_get_contents($url));

        $result = array();

        foreach ($response->result as $chat)
            $result[] = (object)[
                "chat_id" => $chat->message->chat->id,
                "user_name" => $chat->message->chat->first_name
            ];

        return $result;
    }

    private function add_new_message($auto_name, $auto_model, $issue_year, $user_phone, $user_agent)
    {
        $this->db_open();

        $this->create_messages_table_is_not_exist();

        $this->update_messages_table();

        $query = "INSERT INTO messages(auto_name, auto_model, issue_year, user_phone, user_agent) VALUES ('" . $auto_name . "','" . $auto_model . "', '" . $issue_year . "', '" . $user_phone . "', '" . $user_agent . "')";

        $db_result = $this->db->query($query);

        $this->db->close();

        if (!$db_result)
            $this->core->internal_error('Ошибка сервера.', 'Произошла SQL ошибка на сервере.\n' . $this->db->error);
    }

    private function create_chats_table_is_not_exist()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS telegram_chats (
                id int(10) unsigned NOT NULL auto_increment,
                chat_id int(10) unsigned NOT NULL default '0',
                user_name varchar(100) NOT NULL default '',
                PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB";

        $this->db->query($query);
    }

    private function update_chats_table()
    {
        $query = "ALTER TABLE telegram_chats ADD COLUMN active bool default true";

        $this->db->query($query);
    }

    private function create_messages_table_is_not_exist()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS messages (
                id int(10) unsigned NOT NULL auto_increment,
                created_at TIMESTAMP DEFAULT now() ON UPDATE now(),
                auto_name varchar(100),
                auto_model varchar(100),
                issue_year varchar (100),
                user_phone varchar (20),
                PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB";

        $this->db->query($query);
    }

    private function update_messages_table()
    {
        $query = "ALTER TABLE messages ADD COLUMN user_agent varchar(2000)";

        $this->db->query($query);
    }
}