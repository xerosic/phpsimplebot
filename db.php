<?php
$config['ip']       = readLine("IP database (def. Localhost):");
$config['user']     = readLine("User database (def. root):");
$config['database'] = readLine("Nome database:");
$config['password'] = readLine("Password database:");
$config['table']  =  readLine("Table database :");
$dbh                = new PDO("mysql:host=" . $config["ip"] . ";dbname=" . $config['database'], $config['user'], $config['password']);
$table            = $config['table'];
$dbh->query("CREATE TABLE IF NOT EXISTS " . $table . " (
id int(0) AUTO_INCREMENT,
chat_id bigint(0),
username varchar(200),
page varchar(200),
PRIMARY KEY (id))");
echo "Database installed.";
