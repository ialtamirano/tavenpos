<?php

require_once "{INSTALL_DIR}/php-oauth-client/lib/_autoload.php";

try { 
    $a = new \OAuth\Client\Api("demo-oauth-app");
    $a->setUserId("demo-app-user");
    $a->setScope("authorizations");
    $a->setReturnUri("{BASE_URL}/demo-oauth-app/index.php");
    $response = $a->makeRequest("{BASE_URL}/php-oauth/api.php/authorizations/");
    header("Content-Type: application/json");
    echo $response->getContent();
} catch (\OAuth\Client\ApiException $e) {
    echo $e->getMessage();
}
?>
