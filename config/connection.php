<?php

//Development




function getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="root";
    $dbpass="";
    $dbname="tavenposdb";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

ORM::configure('mysql:host=127.0.0.1;dbname=tavenposdb');
ORM::configure('username', 'root');
ORM::configure('password', '');




//Production
/*
function getConnection() {
    $dbhost="tunnel.pagodabox.com";
    $dbuser="dina";
    $dbpass="HdLBrlYX";
    $dbname="tavenposdb";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

ORM::configure('mysql:host=tunnel.pagodabox.com;dbname=tavenposdb');
ORM::configure('username', 'dina');
ORM::configure('password', 'HdLBrlYX');

ActiveRecord\Config::initialize(function($cfg){

    $cfg->set_model_directory('models');
    $cfg->set_connections(array('development' => 'mysql://root:dina@tunnel.pagodabox.com/tavenposdb'));
    //$cfg->set_default_connection('development');

});

*/
?>