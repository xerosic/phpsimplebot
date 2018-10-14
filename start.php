<?php

$token  = "Token"; //Botfather's api key
$admin  =  [430610430]; // ID of the admins
$config = [
    "db" => false, // true for using database
    "ip" => "localhost", 
    "user" => "root", 
    "password" => "password", 
    "database" => "databasename", 
    "table" => "bot", 
    "debug_mode" => false //true for showing all errors and notice
];

$save = array(
    "save",
    "token",
    "config",
    "db",
    "disable",
    "offset",
    "admin" //list of variable
);

if ($config['debug_mode']) {
    error_reporting(E_ALL);
    if ($update){
        echo "Got an update!";
    }
} else {
    error_reporting(0);
}

if ($config['db']) {
    $db = new PDO("mysql:host=" . $config["ip"] . ";dbname=" . $config['database'], $config['user'], $config['password']);
}

echo "[INFO] Starting the bot...\n";
include("functions.php");
$c1     = file_get_contents("http://api.telegram.org/bot$token/getUpdates?offset=-1");
$up1    = json_decode($c1, true);
$offset = $up1["result"][0]["update_id"];
if (!$offset) {
    echo "\n[ERROR] Telegram returned an offset error!\n";
}
echo "[INFO] Starting fetching updates...\n";
while (1) {
    $l = file_get_contents("last.json");
    $content = file_get_contents("http://api.telegram.org/bot$token/getUpdates?offset=$offset");
    if ($l == $content || $content == '{"ok":true,"result":[]}') {
    } else {
        $offset++;
        file_put_contents("last.json", $content);
        $update = json_decode($content, true);
        $update = $update["result"][0];
        include("vars.php");
        if ($config['db']) {
            include("database.php");
        }
    }
    include("bot.php");
    $plugins = scandir("plugins");
    foreach ($plugins as $plugin) {
            include("plugins/$plugin");
        }

        $vars = array_keys(get_defined_vars());
        foreach ($vars as $var) {
            if (in_array($var, $save)) {
            } else {
                unset($$var);
            }
        }
        unset($vars);
}
