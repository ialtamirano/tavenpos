<?php



function getConnection() {

    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE."", DB_USER, DB_PWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

ORM::configure('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.'');
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PWD);




?>