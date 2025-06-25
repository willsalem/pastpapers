<?php

/// Production
$db_name = "pastpapers";
$db_user = "root";
$db_password = "";

try {
    $db = new PDO(dsn: "mysql:host=127.0.0.1;dbname=" . $db_name . ";charset=utf8", username: $db_user, password: $db_password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $th) {
    echo $th->getMessage();
}
const APP_NAME = "pastpapers";