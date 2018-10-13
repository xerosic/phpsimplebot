<?php
$table = $config['table'];
    if ($chatID < 0) {
        $q = $db->query("select * from $table where chat_id = $chatID");
        if (!$q->rowCount()) {
            $db->query("insert into `$table` (chat_id, page, username) values ($chatID, ''," . '"' . $usernamechat . '"' . ")");
        }
    }
    if ($userID) {
        $q = $db->query("select * from $table where chat_id = $userID");
        if (!$q->rowCount()) {
            if ($userID == $chatID) {
                $db->query("insert into `$table` (chat_id, page, username) values ($chatID, ''," . '"' . $username . '"' . ")");
            } else {
                $db->query("insert into `$table` (chat_id, page, username) values ($userID, 'group'," . '"' . $username . '"' . ")");
            }
        } else {
            $u = $q->fetch(PDO::FETCH_ASSOC);
            
            if ($u['page'] == "disable") {
                $db->query("update $table set page = '' where chat_id = $chatID");
            }
            if ($u['page'] == "ban") {
                sm($chatID, "You are banned from this bot");
                $ban = true;
            }
        }
    }