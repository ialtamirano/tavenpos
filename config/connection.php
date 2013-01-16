<?php

//Development

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="tavenposdb";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

ORM::configure('mysql:host=localhost;dbname=tavenposdb');
ORM::configure('username', 'root');
ORM::configure('password', '');


ActiveRecord\Config::initialize(function($cfg){

    $cfg->set_model_directory('models');
    $cfg->set_connections(array('development' => 'mysql://root@localhost/tavenposdb'));
    //$cfg->set_default_connection('development');

});

//Production
/*
function getConnection() {
    $dbhost="tunnel.pagodabox.com";
    $dbuser="analisa";
    $dbpass="gPBehkCR";
    $dbname="tavenposdb";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

ORM::configure('mysql:host=tunnel.pagodabox.com;dbname=tavenposdb');
ORM::configure('username', 'analisa');
ORM::configure('password', 'gPBehkCR');
*/
?>