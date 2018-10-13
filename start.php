<?php

$token  = "TOKEN"; //Botfather's api key
$admin  = array( // ID of the admins 
    430610430,
);
$config = array(
    "db" => true, // true for using database
    "ip" => "localhost", 
    "user" => "root", 
    "password" => "password", 
    "database" => "databasename", 
    "table" => "bot", 
    "debug_mode" => false //true for showing all errors and notice
);
if ($config['db']) {
    $db = new PDO("mysql:host=" . $config["ip"] . ";dbname=" . $config['database'], $config['user'], $config['password']);
}
if ($config['debug_mode']) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

echo "[INFO] Starting the bot...\n";
include("functions.php");
$c1     = file_get_contents("http://api.telegram.org/bot$token/getUpdates?offset=-1");
$up1    = json_decode($c1, true);
$offset = $up1["result"][0]["update_id"];
if (!$offset) {
    echo "\n[ERROR] Telegram returned an offset error!\n";
    exit;
}
echo "[INFO] Starting fetching updates...\n";
while (1) {
    $l       = file_get_contents("last.json");
    $content = file_get_contents("http://api.telegram.org/bot$token/getUpdates?offset=$offset");
    if ($l == $content || $content == '{"ok":true,"result":[]}') {
    } else {
        $offset++;
        $update = json_decode($content, true);
        $update = $update["result"][0];
        include("vars.php");
        if ($config['db']) {
            include("database.php");
        }
    }
    include("comandi.php");
    $plugins = scandir("plugins");
    foreach ($plugins as $plugin) {
            include("plugins/$plugin");
        }
}
